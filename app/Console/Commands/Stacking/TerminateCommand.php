<?php

namespace Cryptounity\Console\Commands\Stacking;

use Illuminate\Console\Command;

use Cryptounity\Stacking;
use Cryptounity\User;
use Cryptounity\Transaction;

use Cryptounity\NXC\NxcUser;
use Cryptounity\NXC\NxcCoin;

use Cryptounity\Mail\BulkTerminated;


use Log;
use DB;
use Mail;

class TerminateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stacking:terminate {--force=0} {--stop-all=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminate all stacking when status is pending terminate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        

        $force = (boolean) $this->option('force');
        $stopAll = (boolean) $this->option('stop-all');
        if( $stopAll ) {
            $this->stopAll();
        }
        else {

        

            if( $force ) {

                $stackings = Stacking::pendingTerminate()->get();

            } else {

                $stackings = Stacking::pendingTerminate()->isNowTime()->get();

            }
            if( count($stackings) > 0 ) {

            
                foreach( $stackings as $stack ) {

                    $user = $stack->user;
                    
                    if(!$user) {
                        Log::error(__METHOD__.'STACKING ID: '.$stack->id.' User ID: '.$stack->user_id.' not found when searching user!');
                        $stack->status = 'inactive';
                        $stack->save();
                        continue;
                    }

                    $walletAddress = $user->wallets()->first();
                    if( !$walletAddress ) {
                        Log::error('User email: '.$user->email.' Wallet not found!');
                        continue;
                    }

                    $nxcUser = NxcUser::where('wallet_coin',$walletAddress->address)->first();
                    if( !$nxcUser ) {
                        Log::error('User email: '.$user->email.' NXC USER not found!');
                        continue;
                    }
                    DB::beginTransaction();
                    $terminFee = $stack->amount * 0.5 / 100;
                    $amount = $stack->amount - $terminFee;
                    $coinCreated = NxcCoin::create([
                        'coin_from' => 1,
                        'coin_to' => $nxcUser->id,
                        'coin_amount' => "$amount",
                        'coin_date' => date('Y-m-d H:i:s'),
                        'coin_txid' => hash('sha256', str_random(30))
                    ])->toSql();
                    
                    $transactionCreated = Transaction::create([
                        'receiver_id' => $user->id,
                        'sender_id' => 1,
                        'creator_id' => 1,
                        'amount' => $amount,
                        'type' => 'credit',
                        'notes' => 'Staking Termination',
                    ]);
                    
                    if( !$coinCreated ) {
                        Log::error(__METHOD__." || $user->email Failed terminate-stack when coin creation");
                        continue;
                    }
                    if( !$transactionCreated ) {
                        Log::error(__METHOD__." || $user->email Failed terminate-stack when transaction creation");
                        continue;
                    }

                    $stack->status = 'inactive';
                    $stack->save();
                    
                    DB::commit();
                    Log::info(__METHOD__." || Success terminate stacking: $user->email");

                }
            } else {
                Log::channel('stack')->info('Nothing to terminate..');
            }
        }
    }

    public function stopAll() {
        $stackings = Stacking::listStackings();
        $coins = [];
        foreach($stackings as $stacking) {

            $user = User::where('username', $stacking->username)->first();
            $wallet = $user->wallets()->first();
            $nxcUser = NxcUser::findByWallet($wallet->address);

            $coin = [
                'coin_from' => 1,
                'coin_to' => $nxcUser->id,
                'coin_amount' => str_replace(',','',$stacking->balance),
                'coin_date' => date('Y-m-d H:i:s'),
                'coin_txid' => str_random(30)
            ];
            array_push($coins, $coin);

        }
        NxcCoin::insert($coins);
        Log::channel('stack')->info('Stacking Terminate: | '.json_encode($coins));

        foreach( $stackings as $stacking ) {
        
            Mail::to('sugamirza2@gmail.com')
            ->send( new BulkTerminated($stacking) );
            Log::channel('stack')->info('Email Sent To '. $stacking->email);
        
        }
    }
}

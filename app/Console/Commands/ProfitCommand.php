<?php

namespace Cryptounity\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use Cryptounity\Stacking;
use Cryptounity\Profit;
use Cryptounity\User;
use Cryptounity\Transaction;

use Log;
use DB;

class ProfitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptounity:profit-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengambil profit harian member cryptounity';

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
        $stackings = Stacking::status('active')->get();
        
        if( !empty($stackings) ) {

            $this->shareLateProfit( $stackings );

        } else {



        }
        
    }

    private function shareLateProfit( $stackings ) {

        $countMsg = 0;
        $msgProfit = "";
        $msgs = [];

        foreach( $stackings as $stack ) {

            // $stackingDate = new Carbon($stack->created_at);
            // $nowDate = Carbon::now();

            $user = $stack->user;
            $omsetGlobal = 0;
            // if( $user->star > 0 ) {

            //     $omsetGlobal = $this->calcOmset( $user );
                
            // }
            
            $profitHarian = $stack->calcProfit();
            DB::beginTransaction();
            $transactions = [
                'receiver_id' => $user->id,
                'sender_id' => env('ADMIN_ID'),
                'creator_id' => env('ADMIN_ID'),
                'amount' => $profitHarian,
                'type' => 'profit_harian',
                'notes' => 'Profit Harian',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $profits = [
                    'user_id' => $user->id,
                    'type' => 'profit_harian',
                    'amount' => $profitHarian,
                    'status' => 'received',
                    'notes' => 'Profit Harian',
                    'created_at' => date('Y-m-d H:i:s')
            ];
            //if( $omsetGlobal !== 0 ) {
                
                // $transactions[] = [
                //     'receiver_id' => $user->id,
                //     'sender_id' => env('ADMIN_ID'),
                //     'creator_id' => env('ADMIN_ID'),
                //     'amount' => $profitHarian,
                //     'type' => 'profit_harian',
                //     'notes' => 'Profit Harian',
                // ];
                // $profits[] = [
                //     'user_id' => $user->id,
                //     'type' => 'profit_harian',
                //     'amount' => $profitHarian,
                //     'status' => 'received',
                // ];
                
            //}
            
            $output = " EMAIL: ".$user->email . implode(' | ',$transactions);

            if(!Transaction::insert($transactions) || !Profit::insert($profits) ){

                
                Log::channel('stack')->error(
                    "FAILED INSERT PROFIT: ".$output
                );
                continue;
            }
            
            DB::commit();
            if( $countMsg >= 10 ) {
                $countMsg = 0;
                $msgProfit = '';
                $msg = implode('||',$msgs);
                $msgs = [];
                Log::channel('stack')->info("SUCCESS INSERT PROFIT: ".$msg);
            }
            
            $msgs[] =$output;
            $countMsg += 1;

        }

    }

    private function calcOmset( User $user ) {

        $downlines = $user->downline( $user->id );
        $omsetGlobal = $this->calcOmset( $downlines );
        $omset = 0;
        
        if( count( $downlines) <= 0 ) {
            return $omset;
        }
        foreach( $downlines as $line ) {
            $omset += $line['stacking'];
        }
        return $omset;

    }
}

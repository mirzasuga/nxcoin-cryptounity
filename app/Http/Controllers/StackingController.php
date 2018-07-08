<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Wallet;
use Cryptounity\Stacking;
use Cryptounity\Transaction;

use Cryptounity\Service\NxccWallet;
use Cryptounity\Service\NxccApiService;
use DB;
use Carbon;
use Log;
class StackingController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();

        if(!$wallet) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Please Add wallet first'
            ]);
            return redirect()->route('wallet');
        }
        $stackings = $user->stackings()->where('status','active')->get();
        return view('stacking.index',[
            'stackings' => $stackings,
            
        ]);
    }

    public function create(Request $request) {
        DB::beginTransaction();
        try {

            $user = auth()->user();
            
            $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();

            if(!$wallet) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Please Add wallet first'
                ]);
                return redirect()->route('wallet');
            }

            $address = $wallet->address;
            $privateKey = $wallet->private_key;
            
            $receiverAddress = env('ADMIN_NXCC_WALLET_ADDRESS');

            $nxccApiService = new NxccApiService($address, $privateKey);

            $nxccWallet = new NxccWallet($address, $receiverAddress, $privateKey);
            $userLevel = $nxccApiService->userLevel();
            $userUpline = $nxccApiService->getReferral();
            
            if( !$userLevel || !$userUpline ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => "Failed stacking, please contact web admin"
                ]);
                return redirect()->back();
            }
            

            $pack = $user->package;
            $amount = (float) $request->amount;
            $min = $pack->min_deposit;
            $max = $pack->max_deposit;
            $userStack = $user->totalStack() + $amount;
            
            

            if( $amount < $min ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => "Total Stacking cannot less than $min"
                ]);

                return redirect()->back();
            }
            
            if( $userStack > $max ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => "Total Stacking cannot more than $max"
                ]);

                return redirect()->back();
            }
            
            if( !$userLevel ) {

                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Stacking failed, please make sure wallet address valid'
                ]);

                return redirect()->back();
            }
            
            
            if( $userLevel->level != $user->level ) {

                $user->level = $userLevel->level;
                $user->save();
            }

            if( $userUpline->user_level == "Promotor" ) {
                // send bonus to user upline
                $cryptoUser = Wallet::where(['address' => $userUpline->data->wallet_coin])->first();
                if($cryptoUser) {

                    $bonusSent = $nxccApiService->sendBonus($userUpline->data,$amount,$userUpline->user_level);
                    if( !$bonusSent ) {
                        session()->flash('alert',[
                            'level' => 'danger',
                            'msg' => "Failed stacking, please contact web admin"
                        ]);
                        return redirect()->back();
                    }
                    $transaction = Transaction::create([
                        'receiver_id' => $cryptoUser->id,
                        'sender_id' => 1,
                        'creator_id' => 0,
                        'amount' => $bonusSent,
                        'type' => 'referral_bonus',
                        'notes' => 'Referral Bonus',
                    ]);

                }
            }

            $stop_at = new Carbon();
            $stop_at = $stop_at->addDays(30);
            
            $stacking = Stacking::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'active',
                'type' => 'credit',
                'termin_fee_percent' => $pack->termination_fee,
                'termin_fee_amount' => $amount * $pack->termination_fee  / 100,
                'stop_at' => $stop_at,
            ]);
            $transaction = Transaction::create([
                'receiver_id' => 1,
                'sender_id' => $user->id,
                'creator_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'notes' => 'Stacking',
            ]);
            if( !$stacking || !$transaction) {

                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Stacking failed, please contact web admin'
                ]);

                return redirect()->back();
            }
            if(!$nxccWallet->debit( $amount ) ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Stacking failed, please contact web admin'
                ]);

                return redirect()->back();
            }
            Log::info('User Stacking| '.$user->id.' Amount: '.$amount.' User level: '.$user->level);

        } catch(\Exception $e) {
            die($e->getMessage());
        }
        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Stacking success'
        ]);
        DB::commit();
        return redirect()->back();
    }

    public function terminate($stackId) {
        DB::beginTransaction();
        $auth = auth()->user();
        $stack = Stacking::where([
            'status' => 'active',
            'id' => $stackId
        ])->firstOrFail();
        if( $stack->user_id != $auth->id) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Sorry you are not stacking this coin'
            ]);
            return redirect()->route('stacking');
        }

        
        $stack->status = 'inactive';
        $amount = $stack->amount - $stack->termin_fee_amount;
        $stack->save();
        Transaction::create([
            'receiver_id' => $auth->id,
            'sender_id' => 1,
            'creator_id' => $auth->id,
            'amount' => $amount,
            'type' => 'credit',
            'notes' => 'Stacking Termination',
        ]);

        $address = env('ADMIN_NXCC_WALLET_ADDRESS'); //admin wallet address
        $key = env('ADMIN_NXCC_WALLET_KEY');
        $privateKey = encrypt($key); // admin wallet key
        
        $receiverAddress = $auth->wallets()->where(['code' => 'NXCC'])->first()->address;
        $nxccWallet = new NxccWallet($address, $receiverAddress, $privateKey);
        if(!$nxccWallet->credit($amount)) {

            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Failed to terminate, please contact web admin'
            ]);
            return redirect()->route('stacking');

        }

        DB::commit();
        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Termination success'
        ]);
        return redirect()->route('stacking');

    }
}
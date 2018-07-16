<?php

namespace Cryptounity\Http\Controllers;

//use Illuminate\Http\Request;
use Cryptounity\Http\Requests\StackingCreateRequest;
use Cryptounity\Wallet;
use Cryptounity\Stacking;
use Cryptounity\Transaction;
use Cryptounity\Bonus;

use Cryptounity\Service\NxccWallet;
use Cryptounity\Service\NxccApiService;
use DB;
use Carbon;
use Log;
use Auth;
class StackingController extends Controller
{

    public function __construct() {
        
    }

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
        // if(!$user->package) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => 'Please Add package first'
        //     ]);
        //     return redirect()->route('package.index');
        // }
        $stackings = $user->stackings()->with('profits')->where('status','active')->first();

        return view('stacking.index',[
            'stackings' => $stackings,
            'user' => $user
        ]);
    }

    public function create(StackingCreateRequest $request) {
        $user = Auth::user();

        if( !$user->can('create-stacking', Stacking::class) ) {
            

            return redirect()->back();
        }

        $data = $request->validated();
        
        DB::beginTransaction();
        
            
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
            $amount = (float) $request->amount;

            
            $response = $nxccWallet->debit( $amount )->getResponse();
            
            if( !$response->success ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => $response->msg
                ]);
                Log::info('User Stacking FAILED | USER ID:'.$user->id.' Email: '.$user->email.' Amount: '.$amount.' User level: '.$user->level);
                return redirect()->back();
            }

            // $pack = $user->package;
            
            // $min = $pack->min_deposit;
            // $max = $pack->max_deposit;
            $userStack = $user->totalStack() + $amount;
            

            $stop_at = new Carbon();
            $stop_at = $stop_at->addDays(30);
            
            $stacking = Stacking::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'active',
                'type' => 'credit',
                'termin_fee_percent' => 0,
                'termin_fee_amount' => 0,
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
            
            Bonus::send($user, $amount);

            if( !$stacking || !$transaction) {

                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Stacking failed, please contact web admin'
                ]);

                return redirect()->back();
            }
            Log::info('User Stacking| USER ID:'.$user->id.' Email'.$user->email.' Amount: '.$amount.' User level: '.$user->level);

        
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

        
        $stack->status = 'pending-terminate';
        $terminFee = $stack->amount * 5 / 100;
        $amount = $stack->amount - $terminFee;
        $stack->save();
        // Transaction::create([
        //     'receiver_id' => $auth->id,
        //     'sender_id' => 1,
        //     'creator_id' => $auth->id,
        //     'amount' => $amount,
        //     'type' => 'credit',
        //     'notes' => 'Stacking Termination',
        // ]);

        // $address = env('ADMIN_NXCC_WALLET_ADDRESS'); //admin wallet address
        // $key = env('ADMIN_NXCC_WALLET_KEY');
        // $privateKey = $key; // admin wallet key
        
        // $receiverAddress = $auth->wallets()->where(['code' => 'NXCC'])->first()->address;
        // $nxccWallet = new NxccWallet($address, $receiverAddress, $privateKey);
        
        // $response = $nxccWallet->credit($amount)->getResponse();
        
        
        // if(!$response->success) {

        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => $response->msg
        //     ]);
        //     return redirect()->route('stacking');

        // }

        DB::commit();
        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Termination success, We will transfer your balance after 2 days.'
        ]);
        return redirect()->route('stacking');

    }
}

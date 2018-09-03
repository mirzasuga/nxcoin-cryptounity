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

// NXCOIN
use Cryptounity\NXC\NxcUser;
use Cryptounity\NXC\NxcCoin;

class StackingController extends Controller
{

    public function __construct() {
        
    }

    public function index() {
        return redirect()->route('dashboard');
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
        return redirect()->route('dashboard');
        $user = Auth::user();

        if( !$user->can('create-stacking', Stacking::class) ) {
            

            return redirect()->back();
        }

        $data = $request->validated();
        
        DB::connection('mysql')->beginTransaction();
        DB::connection('nxc_mysql')->beginTransaction();
        
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
            
            $amount = $request->amount;
            $amount = (double) $amount;

            $nxcUser = NxcUser::findByWallet( $address );
            if(!$nxcUser) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Please makesure your wallet address and nxcoin password is correct.'
                ]);
                return redirect()->back();
            }
            $totalNxcCoin = NxcCoin::total( $nxcUser->id );
            $totalNxcCoin = (double) $totalNxcCoin;

            if( $amount > $totalNxcCoin ) {
                session()->flash('alert',[
                    'level' => 'danger',
                    'msg' => 'Not enough NX Coin, your current balance is: '.$totalNxcCoin.' NXCC'
                ]);
                return redirect()->back();
            }
            
            Stacking::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'active',
                'type' => 'credit'
            ]);
            Transaction::create([
                'receiver_id' => 1,
                'sender_id' => $user->id,
                'creator_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'notes' => 'Stacking',
            ]);
            NxcCoin::create([
                'coin_from' => $nxcUser->id,
                'coin_to' => 1,
                'coin_amount' => $amount,
                'coin_date' => date('Y-m-d H:i:s'),
                'coin_txid' => hash('sha256', str_random(30))
            ]);

            Bonus::send($user, $amount);

        } catch( \Exception $e ) {

            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Stacking failed, please send detail information to support@cryptounity.co'
            ]);
            
            Log::error( $e->getMessage() );
            DB::connection('mysql')->rollBack();
            DB::connection('nxc_mysql')->rollBack();

            return redirect()->back();
        }

        DB::connection('mysql')->commit();
        DB::connection('nxc_mysql')->commit();

        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Stacking success'
        ]);

        Log::info('User Stacking| USER ID:'.$user->id.' Email'.$user->email.' Amount: '.$amount.' User level: '.$user->level.' STATUS:SUCCESS!');
        
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
        $stack->terminate_at = date('Y-m-d');
        $terminFee = $stack->amount * 5 / 100;
        $amount = $stack->amount - $terminFee;
        $stack->save();
        
        DB::commit();
        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Termination success, We will transfer your balance after 2 days.'
        ]);
        return redirect()->route('stacking');

    }
}

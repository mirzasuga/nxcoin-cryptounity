<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Http\Controllers\Controller;
use Cryptounity\Service\NxccWallet;

use Cryptounity\Profit;
use Cryptounity\Transaction;

use Cryptounity\NXC\NxcCoin;
use Cryptounity\NXC\NxcUser;
use DB;
class ProfitController extends Controller
{
    public function take() {
        DB::connection('mysql')->beginTransaction();
        DB::connection('nxc_mysql')->beginTransaction();

        $user           = auth()->user();
        $userWallet     = $user->wallets()->where('code', 'NXCC')->first();
        $profitAmount   = Profit::totalProfit( $user->id );
        $taked          = Profit::takeProfit( $user->id );

        if( $profitAmount <= 0 ) {

            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Your profit is 0, cannot take profit'
            ]);

            return redirect()->route('dashboard');
        }
        if( !$userWallet ) {

            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'please add wallet first'
            ]);

            return redirect()->route('dashboard');
        }

        $address = env('ADMIN_NXCC_WALLET_ADDRESS');
        $key = env('ADMIN_NXCC_WALLET_KEY');
        $receiverAddress = $userWallet->address;

        $transaction = Transaction::create([
            'receiver_id' => $user->id,
            'sender_id' => 1,
            'creator_id' => 1,
            'amount' => $profitAmount,
            'type' => 'credit',
            'notes' => 'Profit Taking',
        ]);
        if(!$transaction) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Taking profit fail, please send detail information to support@cryptounity.co'
            ]);
            return redirect()->back();
        }
        $nxcUser = NxcUser::findByWallet( $userWallet->address );

        if( !$nxcUser ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Wallet not found, please makesure your wallet address and password is correct'
            ]);
            return redirect()->back();
        }
        NxcCoin::create([
            'coin_from' => 1,
            'coin_to' => $nxcUser->id,
            'coin_amount' => $profitAmount,
            'coin_date' => date('Y-m-d H:i:s'),
            'coin_txid' => hash('sha256', str_random(30))
        ]);
        
        DB::connection('mysql')->commit();
        DB::connection('nxc_mysql')->commit();
        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Taking profit success'
        ]);
        return redirect()->back();
        // $nxccWallet = new NxccWallet($address,$receiverAddress, $key);
        // if(!$taked) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => 'Taking bonus fail, please contact web admin'
        //     ]);
        //     return redirect()->back();
        // }
        // $transaction = Transaction::create([
        //     'receiver_id' => $user->id,
        //     'sender_id' => 1,
        //     'creator_id' => 1,
        //     'amount' => $profitAmount,
        //     'type' => 'credit',
        //     'notes' => 'Profit Taking',
        // ]);
        // if(!$transaction) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => 'Taking bonus fail, please contact web admin'
        //     ]);
        //     return redirect()->back();
        // }
        // $response = $nxccWallet->credit($profitAmount)->getResponse();
        // if( !$response->success ) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => $response->msg
        //     ]);
        //     return redirect()->back();
        // }

        // session()->flash('alert',[
        //     'level' => 'success',
        //     'msg' => 'Taking bonus success'
        // ]);
        // DB::commit();
        // return redirect()->back();
    }

}
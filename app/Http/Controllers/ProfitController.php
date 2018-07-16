<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Http\Controllers\Controller;
use Cryptounity\Service\NxccWallet;

use Cryptounity\Profit;
use Cryptounity\Transaction;
use DB;
class ProfitController extends Controller
{
    public function take() {
        DB::beginTransaction();

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

        $nxccWallet = new NxccWallet($address,$receiverAddress, $key);
        if(!$taked) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Taking bonus fail, please contact web admin'
            ]);
            return redirect()->back();
        }
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
                'msg' => 'Taking bonus fail, please contact web admin'
            ]);
            return redirect()->back();
        }
        $response = $nxccWallet->credit($profitAmount)->getResponse();
        if( !$response->success ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => $response->msg
            ]);
            return redirect()->back();
        }

        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Taking bonus success'
        ]);
        DB::commit();
        return redirect()->back();
    }

}
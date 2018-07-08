<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

use Cryptounity\Service\NxccWallet;


use Cryptounity\Bonus;
use Cryptounity\Transaction;
use DB;
class BonusController extends Controller
{
    public function take() {
        DB::beginTransaction();
        $user = auth()->user();
        $userWallet = $user->wallets()->where('code', 'NXCC')->firstOrFail();
        $bonusAmount = Bonus::totalBonus($user->id);
        $taked = Bonus::takeBonus( $user->id );
        if( $bonusAmount <= 0 ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Your bonus is 0, cannot take bonus'
            ]);
            return redirect()->route('dashboard');
        }

        $address = env('ADMIN_NXCC_WALLET_ADDRESS');
        $key = encrypt(env('ADMIN_NXCC_WALLET_KEY'));
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
            'amount' => $bonusAmount,
            'type' => 'credit',
            'notes' => 'Referral Bonus Taking',
        ]);
        if(!$transaction) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Taking bonus fail, please contact web admin'
            ]);
            return redirect()->back();
        }
        
        if( !$nxccWallet->credit($bonusAmount) ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Taking bonus fail, please contact web admin'
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

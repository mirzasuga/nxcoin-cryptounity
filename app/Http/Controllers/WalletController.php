<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Cryptounity\Package;


use Cryptounity\Wallet;
use Cryptounity\Service\NxccWallet;

class WalletController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();

        return view('wallet.index',[
            'wallet' => $wallet
        ]);
    }
    public function create(Request $request) {

        $user = auth()->user();
        $walletUser = new Wallet();
        $walletUser->code = $request->code;
        $walletUser->name = 'NXCC Wallet';
        $walletUser->address = $request->address;
        $walletUser->private_key = encrypt($request->private_key);
        $walletUser->user_id = $user->id;
        
        
        $nxccWallet = new NxccWallet($walletUser->address, NULL, $walletUser->private_key);

        if( !$nxccWallet->validate() ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Wallet Invalid!'
            ]);
            return redirect()->back();
        }
        if( !$walletUser->save()) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Add Wallet Failed!'
            ]);
            return redirect()->back();
        }

        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Wallet Added!'
        ]);
        return redirect()->back();

    }

    public function update(Request $request) {
        $address = $request->address;
        $privateKey = $request->private_key;
        $wallet = Wallet::where(['address' => $address])->first();
        $wallet->private_key = encrypt($privateKey);
        
        $wallet->save();

        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Wallet Updated!'
        ]);
        return redirect()->back();
    }
}

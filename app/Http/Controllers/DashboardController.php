<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();
        $package = $user->package;
        if( !$wallet ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Please add NXCC Wallet'
            ]);
            return redirect()->route('wallet');

        }
        if( !$package ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Add package first!'
            ]);
            return redirect()->route('package.index');
        }

        return view('dashboard.index',[
            'user' => $user,
            'wallet' => $wallet,
            'package' => $package
        ]);
    }
}

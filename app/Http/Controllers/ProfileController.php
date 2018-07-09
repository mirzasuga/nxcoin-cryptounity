<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where('code', 'NXCC')->first();
        $star = $user->getStar();
        
        if(!$wallet) {
            return redirect()->route('wallet');
        }
        return view('profile.index',[
            'user' => $user,
            'wallet' => $wallet,
            'leadership' => $star
        ]);
    }
}

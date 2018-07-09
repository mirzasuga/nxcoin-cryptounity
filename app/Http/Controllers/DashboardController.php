<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Bonus;
use Cryptounity\User;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();
        $package = $user->package;
        
        // $parents = $user->deepParent(3,$user);
        
        
        
        
        
        // $tree = $user->buildTree($downline,'referral_id','id',2);
        //$tree = $user->buildTree($downline, 'id', 'referral_id',$user->id);
        $bonus = Bonus::totalBonus($user->id);
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
            'package' => $package,
            'bonus' => $bonus,
            'downline' => json_encode($downline)
        ]);
    }
}

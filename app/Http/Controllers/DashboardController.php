<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Bonus;
use Cryptounity\User;
use Cryptounity\NXC\NxcUser;
use Cryptounity\NXC\NxcCoin;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
        $wallet = $user->wallets()->where(['code' => 'NXCC'])->first();
        //$package = $user->package;
        $bonus = Bonus::totalBonus($user->id);
        $totalProfit = $user->profits()->status('received')->sum('amount');
        

        if( !$wallet ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Please add NXCC Wallet'
            ]);
            return redirect()->route('wallet');

        }
        // if( !$package ) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => 'Add package first!'
        //     ]);
        //     return redirect()->route('package.index');
        // }
        $nxcUser = NxcUser::findByWallet( $wallet->address );
        if(!$nxcUser) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Please maksure your wallet address and password is correct'
            ]);
            return redirect()->route('wallet');
        }
        $nxcBalance = NxcCoin::total($nxcUser->id);
        
        return view('dashboard.index',[
            'user' => $user,
            'wallet' => $wallet,
            
            'bonus' => $bonus,
            'totalProfit' => $totalProfit,
            'nxcBalance' => $nxcBalance
            
        ]);
    }
}

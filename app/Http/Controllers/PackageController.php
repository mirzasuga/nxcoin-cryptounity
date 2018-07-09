<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

/** MODELS */
use Cryptounity\Package;


use Cryptounity\Wallet;
use Cryptounity\Service\NxccWallet;

use Cryptounity\Stacking;

class PackageController extends Controller
{
    public function index() {
        $packages = Package::all();
        $user = auth()->user();
        $totalStack = $user->totalStack(NULL);
        if($totalStack <= 0) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'You should stacking nxc coin first'
            ]);
            return redirect()->route('stacking');
        }
        return view('package.index')->with([
            'packages' => $packages
        ]);
    }

    public function subscribe(Request $request) {

        $packageId = $request->package_id;
        $user = auth()->user();
        // $packageUser = $user->package()->first();

        // if($packageUser) {
        //     session()->flash('alert',[
        //         'level' => 'danger',
        //         'msg' => 'Already subscribe'
        //     ]);
        //     return redirect()->back();
        // }

        $package = Package::findOrFail($packageId);

        $totalStack = $user->totalStack('active');

        if( $totalStack < $package->min_deposit ) {
            $need = $package->min_deposit - $totalStack;
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'your deposit is not enough, please add '.number_format($need).' stacking to subscribe this package.'
            ]);
            return redirect()->back();
        }

        $walletUser = $user->wallets()->where(['code' => 'NXCC'])->first();
        
        if(!$walletUser) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Please Add wallet first'
            ]);
            return redirect()->route('wallet');
        }
        $address = $walletUser->address;
        $privateKey = $walletUser->private_key;
        
        $nxccWallet = new NxccWallet($address,NULL, $privateKey);
        
        $balance = $nxccWallet->getBalance();
        
        if( $balance < $package->price ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Not enough balance!'
            ]);
            return redirect()->back();
        }
        
        if( !$user->subscribe( $package,$nxccWallet ) ) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Failed Subscribe!'
            ]);
            return redirect()->back();
        }

        session()->flash('alert',[
            'level' => 'success',
            'msg' => 'Success subscribe!'
        ]);
        return redirect()->back();

    }
}

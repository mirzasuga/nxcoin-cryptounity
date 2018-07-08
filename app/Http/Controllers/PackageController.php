<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

/** MODELS */
use Cryptounity\Package;


use Cryptounity\Wallet;
use Cryptounity\Service\NxccWallet;



class PackageController extends Controller
{
    public function index() {
        $packages = Package::all();
        return view('package.index')->with([
            'packages' => $packages
        ]);
    }

    public function subscribe(Request $request) {

        $packageId = $request->package_id;
        $user = auth()->user();
        $packageUser = $user->package()->first();

        if($packageUser) {
            session()->flash('alert',[
                'level' => 'danger',
                'msg' => 'Already subscribe'
            ]);
            return redirect()->back();
        }

        $package = Package::findOrFail($packageId);

        $package = Package::findOrFail($packageId);
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

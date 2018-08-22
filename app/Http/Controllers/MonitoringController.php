<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;
use Cryptounity\Stacking;

class MonitoringController extends Controller
{

    public function __construct() {
        
    }

    public function index() {
        
        if( auth()->user()->email !== 'irwan.satoto@gmail.com' ) {
            return redirect()->route('dashboard');
        }

        $balances = Stacking::listStackings();
        
        return view('monitoring.index')->with([
            'balances' => $balances
        ]);
    }
}

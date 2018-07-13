<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function index() {
        $user = auth()->user();
        //$user->removeFromTree();
        $star = $user->getStar();
        $lines = $user->line()->get();
        
        return view('affiliate.index',[
            'user' => $user,
            'leadership' => $star,
            'lines' => $lines
        ]);
    }
}

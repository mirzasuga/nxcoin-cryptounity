<?php

namespace Cryptounity\Http\Controllers;

use Illuminate\Http\Request;

use Cryptounity\Transaction;
use Cryptounity\User;

class TransactionController extends Controller
{
    public function index() {
        $user = auth()->user();
        $transactions = $user->transactions()->orderBy('id','desc')->paginate(15);
        
        return view('transaction.index',[
            'transactions' => $transactions
        ]);
    }


}

<?php

namespace Cryptounity\Http\Controllers\Api;

use Illuminate\Http\Request;
use Cryptounity\Http\Controllers\Controller;

use Cryptounity\Stacking;

class DepositController extends Controller
{
    public function total() {
        
        try {
            $totalStack = Stacking::total();
            return response()->json([
                'success' => 1,
                'msg' => true,
                'data' => [
                    'total_deposit' => number_format($totalStack,2)
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => 0,
                'msg' => $e->getMessage(),
                'data' => [
                    'total_deposit' => 0
                ]
            ]);
        }
    }
}

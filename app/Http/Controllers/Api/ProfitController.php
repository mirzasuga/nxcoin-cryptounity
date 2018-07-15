<?php

namespace Cryptounity\Http\Controllers\Api;

use Illuminate\Http\Request;
use Cryptounity\Http\Controllers\Controller;

use Cryptounity\Profit;
class ProfitController extends Controller
{
    public function total() {

        try {
            $profits = Profit::total();
            return response()->json([
                'success' => 1,
                'msg' => true,
                'data' => [
                    'total_profit' => number_format($profits,2)
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => 0,
                'msg' => $e->getMessage(),
                'data' => [
                    'total_profit' => 0
                ]
            ]);
        }

    }
}

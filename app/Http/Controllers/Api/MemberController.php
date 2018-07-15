<?php

namespace Cryptounity\Http\Controllers\Api;

use Illuminate\Http\Request;
use Cryptounity\Http\Controllers\Controller;

use Cryptounity\User;

class MemberController extends Controller
{

    public function total() {

        try {
            $member = User::get()->count();
            return response()->json([
                'success' => 1,
                'msg' => true,
                'data' => [
                    'total_member' => $member
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => 0,
                'msg' => $e->getMessage(),
                'data' => [
                    'total_member' => 0
                ]
            ]);
        }

    }

}

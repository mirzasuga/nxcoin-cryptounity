<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\User;

use DB;
class Bonus extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
    ];

    public function user() {

    }

    public static function takeBonus($userId) {
        return DB::table('bonuses')
        ->where('user_id', $userId)
        ->update([
            'status' => 'take'
        ]);
    }
    public static function totalBonus($userId) {
        $totalBonus = DB::table('bonuses')->where([
            'user_id' => $userId,
            'status' => 'active'
        ])->sum('amount');
        return $totalBonus;
    }
}

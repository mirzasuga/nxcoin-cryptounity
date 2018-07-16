<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\User;
use DB;

class Profit extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'notes',
    ];

    public function user() {

    }

    public static function total() {

        return DB::table('profits')->where('status','received')->sum('amount');
        
    }
    public static function totalProfit($userId) {
        return DB::table('profits')->where([
            'status' => 'received',
            'user_id' => $userId
        ])->sum('amount');
    }
    public static function takeProfit($userId) {
        return DB::table('profits')
        ->where('user_id', $userId)
        ->update([
            'status' => 'take'
        ]);
    }

    public function scopeStatus($query,$status) {
        return $query->where('status',$status);
    }
}

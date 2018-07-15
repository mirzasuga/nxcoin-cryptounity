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
}

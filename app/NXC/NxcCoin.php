<?php

namespace Cryptounity\NXC;

use Illuminate\Database\Eloquent\Model;

class NxcCoin extends Model
{
    protected $connection = 'nxc_mysql';

    protected $table = 'tb_coin';

    protected $primaryKey = 'coin_id';

    protected $fillable = [
        'coin_from',
        'coin_to',
        'coin_amount',
        'coin_date',
        'coin_txid'
    ];
    public $timestamps = false;

    public static function total($userId) {
        $received = self::where('coin_from',$userId)->sum('coin_amount');
        $sent = self::where('coin_to', $userId)->sum('coin_amount');
        return $sent - $received;
    }

}

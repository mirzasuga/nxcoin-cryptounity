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
}

<?php

namespace Cryptounity\NXC;

use Illuminate\Database\Eloquent\Model;

use DB;
class NxcUser extends Model
{
    protected $connection = 'nxc_mysql';

    protected $table = 'tb_users';

    // protected $fillable = [

    // ];

    public function referral() {
        return $this->hasOne(User::class,'referral_id','id');
    }

    public function scopeWalletCoin($query,$address) {

        return $query->where('wallet_coin', $addre);

    }

    public static function findByWallet( $address ) {

        return self::where('wallet_coin', $address)->first();

    }
}

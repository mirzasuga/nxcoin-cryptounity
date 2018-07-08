<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\Service\WalletService;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = [
        'code',
        'name',
        'address',
        'private_key',
        'balance'
    ];
    protected $hidden = [
        'private_key'
    ];

    protected $walletService;

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}

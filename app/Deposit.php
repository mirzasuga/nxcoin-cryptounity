<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;


use Cryptounity\User;

class Deposit extends Model
{

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'status',
        'type',
        'fee',
        'fee_amount'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}

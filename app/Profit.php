<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\User;

class Profit extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
    ];

    public function user() {

    }
}

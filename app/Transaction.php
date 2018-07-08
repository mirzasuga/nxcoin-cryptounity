<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'receiver_id',
        'sender_id',
        'creator_id',
        'amount',
        'type',
        'notes',
    ];


}

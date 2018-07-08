<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;
use Cryptounity\User;
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

    public function user() {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }


}

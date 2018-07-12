<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\User;
use Cryptounity\Package;

use DB;
class Stacking extends Model
{
    protected $table = 'stackings';
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'type',
        'termin_fee_percent',
        'termin_fee_amount',
        'stop_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function scopeStatus($query, $status) {
        return $query->where('status',$status);
    }
    // public function terminate() {
        
    // }
}

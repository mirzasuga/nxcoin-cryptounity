<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;


use Cryptounity\User;

class Package extends Model
{
    protected $table = 'packages';
    protected $fillable = [
        'name',
        'price',
        'profit_percent',
        'min_deposit',
        'max_deposit',
        'termination_fee',
        'multiple_account',
        'access_all_feature',
    ];

    public function subscribe(User $user) {

        

    }

    public function users() {
        return $this->hasMany(User::class);
    }
}

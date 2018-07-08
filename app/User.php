<?php

namespace Cryptounity;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Cryptounity\Wallet;
use Cryptounity\Package;
use Cryptounity\Stacking;
use Cryptounity\Service\WalletService;

use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wallets() {
        return $this->hasMany(Wallet::class,'user_id');
    }
    public function package() {
        return $this->belongsTo(Package::class,'package_id');
    }
    public function stackings() {
        return $this->hasMany(Stacking::class,'user_id');
    }

    public function subscribe( Package $package, WalletService $walletService ) {
        DB::beginTransaction();
        $this->package_id = $package->id;

        if( !$this->save() ) {
            return false;
        }

        DB::commit();
        return true;
    }

    public function totalStack() {
        $total = DB::table('stackings')->where([
            'user_id' => $this->id,
            'status' => 'active'
        ])->sum('amount');
        return $total;
    }
}

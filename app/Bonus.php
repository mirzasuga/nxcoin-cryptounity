<?php

namespace Cryptounity;

use Illuminate\Database\Eloquent\Model;

use Cryptounity\User;

use DB;
class Bonus extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'notes'
    ];

    public function user() {

    }

    public static function takeBonus($userId) {
        return DB::table('bonuses')
        ->where('user_id', $userId)
        ->update([
            'status' => 'take'
        ]);
    }
    public static function totalBonus($userId) {
        $totalBonus = DB::table('bonuses')->where([
            'user_id' => $userId,
            'status' => 'active'
        ])->sum('amount');
        return $totalBonus;
    }

    //send bonus to all parents
    public static function send($user, $stackingAmount) {
        $parents = $user->deepParent(10,$user);
        $config = config('bonus');
        
        $useConfig = null;
        
        foreach( $config as $k => $c ) {
            $min = (float) $c['min'];
            $max = (float) $c['max'];
            if( $stackingAmount >= $min && $stackingAmount <= $max ) {
                $useConfig = $c;
                continue;
            }
            
        }
        $parents = $parents->parents;
        $i=0;
        foreach( $useConfig['shares'] as $share ) {
            
            $bonus = $stackingAmount * $share / 100;
            if(isset($parents[$i])) {
                self::create([
                    'user_id' => $parents[$i]->id,
                    'type' => 'referral_bonus',
                    'amount' => $bonus,
                    'notes' => $user->username." Stacking",
                    'status' => 'active'
                ]);
            }
            $i++;
            
        }
        
    }
}

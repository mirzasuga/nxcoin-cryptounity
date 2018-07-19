<?php

namespace Cryptounity;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Cryptounity\Wallet;
use Cryptounity\Package;
use Cryptounity\Stacking;
use Cryptounity\Transaction;
use Cryptounity\Profit;
use Cryptounity\Service\WalletService;
use DB;
use Log;

/**
 * NXCOIN
 */
use Cryptounity\NXC\NxcUser;

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
        'username',
        'referral_id',
        'email',
        'password',
        'members',
        'star',
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
    public function profits() {
        return $this->hasMany(Profit::class,'user_id');
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

    public function totalStack($status = 'active', $userId = NULL) {
        $id = ($userId == NULL) ? $this->id : $userId;
        if( $status == NULL ) {
            $total = DB::table('stackings')->where([
                'user_id' => $id,
            ])->sum('amount');

        } else {
            $total = DB::table('stackings')->where([
                'user_id' => $id,
                'status' => $status
            ])->sum('amount');
        }
        return $total;
    }

    public function receive() {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }
    public function sent() {
        return $this->hasMany(Transaction::class, 'sender_id');
    }
    public function transactions() {
        return Transaction::where('receiver_id',$this->id)->orWhere('sender_id',$this->id)->orderBy('created_at','desc');
    }
    public function totalProfit() {
        $profit = DB::table('transactions')->where([
            'receiver_id' => $this->id,
            'type' => 'referral_bonus'
        ])->orWhere([
            'receiver_id' => $this->id,
            'type' => 'daily_earnings'
        ])->sum('amount');
    }

    public function allNet() {
        return DB::table('users')->select('id');
    }
    public function line() {
        return $this->hasMany(User::class, 'referral_id');
    }
    public function parent() {
        return $this->hasOne(User::class,'id','referral_id');
    }

    public function deepParent($deep, $user) {
        $parents = [];
        for( $i=1; $i <= $deep; $i++ ) {
            if( $user ) {
                if( $user->id == $user->referral_id ) {
                    break;
                }
                $parent = $user->parent()->first();
                if( !empty($parent) && $parent->referral_id != 0 ) {
                    $parents[] = $parent;
                    $user = $parent;
                    
                }
            }

        }
        
        // foreach( $parents as $p) {
        //     $data[] = [
        //         'id' => $p->id,
        //         'referral_id' => $p->referral_id,
        //         'username' => $p->username,
        //         'omset' => $p->totalStack('active')
        //     ];

        // }
        $this->parents = $parents;
        return $this;
    }

    public function downline($id) {
        $line = [];
        $data = DB::select(DB::raw('
        SELECT `id`,
			 `username`,
			 `referral_id`
        FROM (
            SELECT * FROM users ORDER BY `referral_id`, `id`
        ) tb_users_sorted, (
            SELECT @pv := '.$id.') initialisation
        WHERE FIND_IN_SET(`referral_id`, @pv) > 0 AND @pv := CONCAT(@pv, ",", `id`)
        '));
        
        

        //convert to array
        $struct = [];
        foreach( $data as $item ) {
            
            $struct[] = [
                'id' => $item->id,
                'referral_id' => $item->referral_id,
                'username' => $item->username,
                'stacking' => $this->totalStack('active', $item->id)
            ];
        }
        
        return $struct;
    }
    function buildTree($flat, $pidKey, $idKey = null,$rootId)
    {
        $grouped = array();
        foreach ($flat as $sub){
            $grouped[$sub[$pidKey]][] = $sub;
        }

        $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if(isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }

            return $siblings;
        };
        
        $tree = $fnBuilder($grouped[$rootId]);

        return $tree;
    }
    

    //DECIDE USER LEADERSHIP
    public function getStar() {
        
        $lines = $this->line()->get();
        $star[5] = 0;
        $star[4] = 0;
        $star[3] = 0;
        $star[2] = 0;
        $star[1] = 0;
        $star['member'] = 0;
        $c = 0;
        foreach( $lines as $line ) {
            $star[5] += ($line->star == 5) ? 1 : 0;
            $star[4] += ($line->star == 4) ? 1 : 0;
            $star[3] += ($line->star == 3) ? 1 : 0;
            $star[2] += ($line->star == 2) ? 1 : 0;
            $star[1] += ($line->star == 1) ? 1 : 0;
            $star['member'] += ($line->totalStack() >= 50) ? 1 : 0;
        }
        
        foreach( $star as $key => $item ) {
            if( $item ) {
                return $key;
            }
            else if($key == 'member') {

                switch ($item) {
                    case $item >= 7 && $item <= 342:
                        return 1;
                        break;
                    case $item >= 343 && $item <= 2400:
                        return 3;
                        break;
                    case $item >= 2401 && $item <= 16806:
                        return 4;
                        break;
                    case $item >= 16806:
                        return 5;
                        break;
                    default:
                        return 0;
                        break;
                }

            }
        }
    }

    /**
     * Jika user tidak stacking selama 3 hari
     * maka pindahkan downline ke atas referral user ini
     */
    public function removeFromTree() {

        
        if( $this->id == 1 ) {return;}

        DB::beginTransaction();

        /**
         * get referral_id from this user;
         */
        $moveTo = $this->referral_id;

        $downlines = $this->line()->get();
        if( count($downlines) > 0 ) {

            $parent = $this->parent()->first();

            while ($this->parent) {
                
            }

            $directDownlineId = [];
            foreach( $downlines as $line ) {

                $directDownlineId[] = $line->id;

            }
            $directDownlineId = implode( ',',$directDownlineId );
            $sql = "
                UPDATE users set referral_id='$moveTo' where id in ($directDownlineId);
            ";
            
            if(!DB::statement($sql)) {

                Log::error('FAILED REMOVE ID: '.$this->id.' FROM TREE ('.$directDownlineId.')');
                return false;
            }
        }
        DB::commit();
        return true;
    }

    
    
}

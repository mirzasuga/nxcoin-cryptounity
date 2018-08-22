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

    const PROFIT_PERCENTAGE = [
        [ 'min' => 0, 'max' => 100, 'percent' => 0.3 ],
        [ 'min' => 101, 'max' => 2500, 'percent' => 0.5 ],
        [ 'min' => 2501, 'max' => 5000, 'percent' => 0.7 ],
        [ 'min' => 5001, 'max' => 10000, 'percent' => 0.9 ],
        [ 'min' => 10001, 'max' => 10000000, 'percent' => 1.1 ]
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profits() {
        return $this->hasMany(Profit::class,'stacking_id');
    }

    
    public function scopeStatus($query, $status) {

        if( is_array($status) ) {
            return $query->whereIn('status',$status);
        }
        
        return $query->where('status',$status);
    }
    

    public function calcProfit( $stackAmount = null ) {

        $amount = floatval(($stackAmount === null) ? $this->amount : $stackAmount);

        $percent = $this->decidePercentage( $amount );

        $profitHarian = number_format($amount * $percent / 100,8);
        return $profitHarian;
        

    }

    private function decidePercentage( $input ) {

        //$precision = 0.00001 ;
        $rangeArray = self::PROFIT_PERCENTAGE;
        $percentage = 0;
        foreach($rangeArray as $current) {

            if( $input >= $current['min'] and $input <= $current['max'] ) {
                return $percentage = floatval($current['percent']);
            } else if( $input > 10000000 ) {
                $percentage = 1.1; //MAX PERCENTAGE    
            }
            // if( $input == 2501) { dd($input - $current['min']); }
            // if( ($input - $current['min']) >= $precision and ($input - $current['max']) <= $precision ) {

            //     $percentage = floatval($current['percent']);

            //     break;

            // } else if( $input > 10000000 ) {
            //     $percentage = 1.1; //MAX PERCENTAGE
            // }

        }

        return $percentage;
    }
    public static function total() {

        return DB::table('stackings')->where('status','active')->sum('amount');

    }

    public function scopePendingTerminate($query) {

        return $query->where('status','pending-terminate');

    }
    public function scopeIsNowTime($query) {
        $now = date('Y-m-d H:i:s');
        
        return $query->whereRaw('TIMESTAMPDIFF(DAY, terminate_at, NOW() ) >= 2');
    }

    public static function listStackings() {
        return DB::select(
            DB::raw("
            select u.email,u.username,sum(s.amount) as balance
            from users u
            join stackings s on u.id = s.user_id
            where s.status='active'
            group by s.user_id,u.email,u.username
            order by balance desc
        "));
    }
}

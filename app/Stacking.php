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

    
    public function scopeStatus($query, $status) {

        if( is_array($status) ) {
            return $query->whereIn('status',$status);
        }
        
        return $query->where('status',$status);
    }
    

    public function calcProfit( $stackAmount = null ) {

        $amount = ($stackAmount === null) ? $this->amount : $stackAmount;

        $percent = $this->decidePercentage( $amount );

        $profitHarian = $amount * $percent / 100;
        
        return $profitHarian;
        

    }

    private function decidePercentage( $input ) {

        $precision = 0.00001 ;
        $rangeArray = self::PROFIT_PERCENTAGE;
        $percentage = 0;
        foreach($rangeArray as $current) {

            if( ($input - $current['min']) > $precision and ($input - $current['max']) <= $precision ) {
                $percentage = $current['percent'];
                break;
            } else if( $input > 10000000 ) {
                $percentage = 1.1; //MAX PERCENTAGE
            }

        }

        return $percentage;
    }
}

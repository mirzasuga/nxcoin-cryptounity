<?php

namespace Cryptounity\Service;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Cryptounity\Service\NxccWallet;


class NxccApiService
{
    protected $apiEndpoint;
    private $response;
    protected $address, $privateKey;

    public function __construct( $address = NULL, $privateKey = NULL ) {
        $this->apiEndpoint = env('NXCOIN_API_ENDPOINT');
        $this->address = $address;
        $this->privateKey = $privateKey;
    }

    public function userLevel() {
        $params = [
            'wallet_address' => $this->address,
            'private_key' => decrypt($this->privateKey),
        ];
        $query = base64_encode(http_build_query($params));
        
        $url = $this->apiEndpoint.'/user/level?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        $this->response = $response;
        if( !$response->success ) {
            $this->setLogError();
            return null;
        }
        return $response;
    }
    public function getReferral() {
        $params = [
            'wallet_address' => $this->address,
            'private_key' => decrypt($this->privateKey),
        ];
        $query = base64_encode(http_build_query($params));
        
        $url = $this->apiEndpoint.'/user/upline?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        $this->response = $response;
        if( !$response->success ) {
            $this->setLogError();
            return null;
        }
        return $response;
    }

    //stacking bonus if referral promotor / global promotor
    public function sendBonus($userData, $stacking_amount, $level) {
        $receiverAddress = $userData->wallet_coin;
        $senderAddress = env('ADMIN_NXCC_WALLET_ADDRESS');
        $senderKey = encrypt(env('ADMIN_NXCC_WALLET_KEY'));

        // $nxccWallet = new NxccWallet($senderAddress, $receiverAddress, $senderKey);
        $bonusPercent = 0;
        switch ($level) {
            case 'Promotor':
                $bonusPercent = env('PROMOTOR_BONUS_PERCENT');
                break;
            case 'Global Promotor':
                $bonusPercent = env('GLOBAL_PROMOTOR_BONUS_PERCENT');
                break;
        }
        $amount = $stacking_amount * $bonusPercent / 100;

        // if( !$nxccWallet->credit($amount) ) {
        //     return false;
        // }
        return $amount;

    }


    private function setLogError() {
        $response = 'RESPONSE CODE: '.$this->response->code.'| RESPONSE MESSAGE: '.$this->response->msg;
        $message = 'FAILED CALL| ADDRESS: '.$this->address.'||'.$response;
        Log::error($message);
    }

    
}
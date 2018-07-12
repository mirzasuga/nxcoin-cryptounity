<?php

namespace Cryptounity\Service;

use Cryptounity\Service\WalletService as BaseWallet;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class NxccWallet extends BaseWallet
{
    protected $apiEndpoint;
    private $response;
    protected $address, $privateKey, $receiverAddress;

    public function __construct( $address = NULL, $receiverAddress =NULL, $privateKey = NULL ) {
        $this->apiEndpoint = env('NXCOIN_API_ENDPOINT');
        $this->address = $address;
        $this->receiverAddress = $receiverAddress;
        $this->privateKey = $privateKey;
    }

    public function call() {}

    public function getBalance() {

        
        $params = [
            'wallet_address' => $this->address,
            'private_key' => decrypt($this->privateKey)
        ];
        
        $query = base64_encode(http_build_query($params));
        
        $url = $this->apiEndpoint.'/wallet/get-balance?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        
        $this->response = $response;
        if( !$response->success ) {
            $this->setLogError();
            return false;
        }
        return $response->balance;
        
    }
    public function debit( $amount ) {

        $params = [
            'wallet_address' => $this->address,
            'receiver_address' => $this->receiverAddress,
            'private_key' => decrypt($this->privateKey),
            'amount' => $amount
        ];
        $query = base64_encode(http_build_query($params));
        
        $url = $this->apiEndpoint.'/wallet/debit?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        $this->response = $response;
        if( !$response->success ) {
            $this->setLogError();
            return false;
        }
        return true;

    }

    //credit from admin to receiver
    public function credit( $amount ) {

        $params = [
            'wallet_address' => $this->address,
            'receiver_address' => $this->receiverAddress,
            'private_key' => decrypt($this->privateKey),
            'amount' => $amount
        ];
        $query = base64_encode(http_build_query($params));
        $url = $this->apiEndpoint.'/wallet/credit?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        $this->response = $response;
        Log::info(__METHOD__.' FROM: '.$this->address.' TO: '.$this->receiverAddress.print_r($response));
        if( !$response ) {
            $this->setLogError();
            return false;
        }
        return true;

    }

    public function validate() {
        
        $params = [
            'wallet_address' => $this->address,
            'private_key' => decrypt($this->privateKey)
        ];
        $query = base64_encode(http_build_query($params));
        $url = $this->apiEndpoint.'/wallet/validate?v='.$query;
        
        $response = json_decode(Curl::to($url)->get());
        $this->response = $response;

        if( !$response->success ) {
            $this->setLogError();
            return false;
        }
        return true;
    }

    private function setLogError() {
        if($this->response) {
            $response = 'RESPONSE CODE: '.$this->response->code.'| RESPONSE MESSAGE: '.$this->response->msg;
            $message = 'FAILED CALL|ADDRESS: '.$this->address.'||'.$response;
        } else {
            $message = 'CALL FAILED WITH NO RESPONSE';
        }
        Log::error($message);
    }
}
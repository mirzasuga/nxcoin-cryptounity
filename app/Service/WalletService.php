<?php

namespace Cryptounity\Service;
use Ixudra\Curl\Facades\Curl;
abstract class WalletService
{
    protected $address, $privateKey, $amount;

    abstract public function getBalance();
    abstract public function debit($amount);
    abstract public function credit( $amount );
    abstract public function call();
}
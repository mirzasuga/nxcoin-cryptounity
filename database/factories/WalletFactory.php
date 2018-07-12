<?php

use Faker\Generator as Faker;

$factory->define(Cryptounity\Wallet::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Uuid($faker));
    return [
        'code' => 'NXCC',
        'name' => 'NXCC WALLET',
        'address' => $faker->uuid
    ];
});

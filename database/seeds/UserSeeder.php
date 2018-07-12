<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(Cryptounity\User::class,4)
            ->create()
            ->each( function($u) {
                $u->wallets()->save(factory(Cryptounity\Wallet::class)->make());
            });
    }
}

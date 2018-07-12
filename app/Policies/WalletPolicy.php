<?php

namespace Cryptounity\Policies;

use Cryptounity\User;
use Cryptounity\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the wallet.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Wallet  $wallet
     * @return mixed
     */
    public function view(User $user, Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can create wallets.
     *
     * @param  \Cryptounity\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the wallet.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Wallet  $wallet
     * @return mixed
     */
    public function update(User $user, Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can delete the wallet.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Wallet  $wallet
     * @return mixed
     */
    public function delete(User $user, Wallet $wallet)
    {
        //
    }
}

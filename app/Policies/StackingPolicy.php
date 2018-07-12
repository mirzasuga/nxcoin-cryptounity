<?php

namespace Cryptounity\Policies;

use Cryptounity\User;
use Cryptounity\Stacking;
use Illuminate\Auth\Access\HandlesAuthorization;

class StackingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the stacking.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Stacking  $stacking
     * @return mixed
     */
    public function view(User $user, Stacking $stacking)
    {
        //
    }

    /**
     * Determine whether the user can create stackings.
     *
     * @param  \Cryptounity\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
        // $stacking = $user->stackings()->status('active')->first();
        // if(!$stacking) {
        //     return true;
        // }
        // return true;
    }

    /**
     * Determine whether the user can update the stacking.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Stacking  $stacking
     * @return mixed
     */
    public function update(User $user, Stacking $stacking)
    {
        //
    }

    /**
     * Determine whether the user can delete the stacking.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\Stacking  $stacking
     * @return mixed
     */
    public function delete(User $user, Stacking $stacking)
    {
        //
    }
}

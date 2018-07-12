<?php

namespace Cryptounity\Policies;

use Cryptounity\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Cryptounity\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Cryptounity\User  $user
     * @param  \Cryptounity\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }
}

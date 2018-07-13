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
        
        $stacking = $user->stackings()->status(['active','pending-terminate'])->get();
        
        if(count($stacking) > 0) {
            foreach( $stacking as $i ) {
                if( $i->status === 'pending-terminate' ) {
                    session()->flash('alert',[
                        'level' => 'danger',
                        'msg' => 'You have a pending terminatation, please wait until your termination approve'
                    ]);
                } else {
                    session()->flash('alert',[
                        'level' => 'danger',
                        'msg' => 'Please terminate your last staking.'
                    ]);
                }
            }
            return false;
        }
        return true;
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

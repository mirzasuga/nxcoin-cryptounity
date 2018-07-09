<?php

namespace Cryptounity\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Registered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    protected $referral;
    public function __construct($user, $referral)
    {
        $this->user = $user;
        $this->referral = $referral;
        $this->onRegistered();
    }

    public function onRegistered() {
        $parents = $this->user->deepParent(5,$this->user);
        foreach( $parents->parents as $parent ) {
            $parent->members += 1;
            $parent->save();
        }
        $refId = $this->referral->id;

    }

    
}


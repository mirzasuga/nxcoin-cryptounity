<?php

namespace Cryptounity\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BulkTerminated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $stacking;
    public function __construct($stacking)
    {
        $this->stacking = $stacking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('info@cryptounity.co')
        ->subject('Staking Termination')
        ->markdown('mails.stacking-terminated')
        ->with([
            'stacking' => $this->stacking
        ]);
    }
}

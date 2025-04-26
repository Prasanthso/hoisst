<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RmThresholdExceededMail extends Mailable
{
    use Queueable, SerializesModels;

    public $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function build()
    {
        return $this->subject('Raw Material Price Threshold Exceeded')
            ->view('emails.rm_threshold_exceeded')->with(['materials' => $this->materials]);
    }
}

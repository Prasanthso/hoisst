<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PmThresholdExceededMail extends Mailable
{
    use Queueable, SerializesModels;

    public $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function build()
    {
        return $this->subject('Packing Material Price Threshold Exceeded')
            ->view('emails.pm_threshold_exceeded')->with(['materials' => $this->materials]);;
    }
}

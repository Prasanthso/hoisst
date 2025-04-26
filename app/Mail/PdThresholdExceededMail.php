<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PdThresholdExceededMail extends Mailable
{
    use Queueable, SerializesModels;

    public $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function build()
    {
        return $this->subject('Product Price Threshold Exceeded')
            ->view('emails.pd_threshold_exceeded')->with(['materials' => $this->materials]);
    }
}

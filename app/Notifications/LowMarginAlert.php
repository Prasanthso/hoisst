<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowMarginAlert extends Mailable
{
    public $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function build()
    {
        return $this->subject('Low Margin Alert')
            ->view('emails.low_margin_alert');
    }
}

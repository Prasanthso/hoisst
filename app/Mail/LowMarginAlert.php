<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowMarginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $marginPercentage;

    public function __construct($productName, $marginPercentage)
    {
        $this->productName = $productName;
        $this->marginPercentage = $marginPercentage;
    }

    public function build()
    {
        return $this->subject("Low Margin Alert for {$this->productName}")
            ->view('emails.low_margin_alert')
            ->with([
                'productName' => $this->productName,
                'marginPercentage' => $this->marginPercentage,
            ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RmPriceUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }

    public function build()
    {
        return $this->subject("Price Update Alert for Multiple Materials")
            ->view('emails.rm_price_update')
            ->with([
                'materials' => $this->materials
            ]);
    }
}

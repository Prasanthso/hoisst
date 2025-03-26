<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\RawMaterial;

class RmPriceUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $material;
    public $newPrice;

    public function __construct(RawMaterial $material, $newPrice)
    {
        $this->material = $material;
        $this->newPrice = $newPrice;
    }

    public function build()
    {
        return $this->subject("Price Update Alert: {$this->material->name}")
            ->view('emails.rm_price_update')
            ->with([
                'materialName' => $this->material->name,
                // 'newPrice' => $this->newPrice,
                'viewUrl' => url('/raw-materials/' . $this->material->id),
            ]);
    }
}

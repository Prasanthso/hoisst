<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PmPriceUpdateNotification extends Notification
{
    use Queueable;

    use Queueable;

    protected $packingMaterial;
    protected $newPrice;

    public function __construct($packingMaterial, $newPrice)
    {
        $this->packingMaterial = $packingMaterial;
        $this->newPrice = $newPrice;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can also add 'database', 'slack', or 'broadcast' here
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Price Update Frequency Alert: ' . $this->packingMaterial->name)
            ->line('The price of ' . $this->packingMaterial->name . ' has been updated to ' . $this->newPrice)
            ->action('View Material', url('/editpackingmaterials/' . $this->packingMaterial->id))
            ->line('Please review the updated price.');
    }
}

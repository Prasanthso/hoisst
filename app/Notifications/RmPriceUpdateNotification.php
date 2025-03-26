<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RmPriceUpdateNotification extends Notification
{
    use Queueable;

    use Queueable;

    protected $rawMaterial;
    protected $newPrice;

    public function __construct($rawMaterial, $newPrice)
    {
        $this->rawMaterial = $rawMaterial;
        $this->newPrice = $newPrice;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can also add 'database', 'slack', or 'broadcast' here
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Price Update Frequency Alert: ' . $this->rawMaterial->name)
                    ->line('The price of ' . $this->rawMaterial->name . ' has been updated to ' . $this->newPrice)
                    ->action('View Material', url('/raw-materials/' . $this->rawMaterial->id))
                    ->line('Please review the updated price.');
    }
}

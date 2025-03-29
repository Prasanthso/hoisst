<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PdPriceUpdateNotification extends Notification
{
    use Queueable;

    use Queueable;

    protected $product;
    protected $newPrice;

    public function __construct($product, $newPrice)
    {
        $this->product = $product;
        $this->newPrice = $newPrice;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can also add 'database', 'slack', or 'broadcast' here
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Price Update Frequency Alert: ' . $this->product->name)
            ->line('The price of ' . $this->product->name . ' has been updated to ' . $this->newPrice)
            ->action('View Material', url('/editproduct/' . $this->product->id))
            ->line('Please review the updated price.');
    }
}

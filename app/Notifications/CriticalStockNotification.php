<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CriticalStockNotification extends Notification
{
    use Queueable;

    protected $stock;

    /**
     * Create a new notification instance.
     */
    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase( $notifiable)
    {
        return [
            'message' => "This item is out of stock. Restock now.",
            'item' => $this->stock->item,
            'category' => $this->stock->category,
            'quantity' => $this->stock->quantity,
            'stocking_point' => $this->stock->stocking_point,
        ];
    }
    

   
}

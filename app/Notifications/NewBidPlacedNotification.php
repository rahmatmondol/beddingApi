<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBidPlacedNotification extends Notification
{
    use Queueable;

    protected $betting;

    public function __construct($betting)
    {
        $this->betting = $betting;
    }

    public function via($notifiable)
    {
        return ['database']; // Store notification in the database
    }

    public function toDatabase($notifiable)
    {
        return [
            'betting_id' => $this->betting->id,
            'user_id' => $this->betting->user_id,
            'service_id' => $this->betting->service_id,
            'bid_amount' => $this->betting->betting_amount,
            'message' => 'A new bid has been placed on your service.'
        ];
    }
}

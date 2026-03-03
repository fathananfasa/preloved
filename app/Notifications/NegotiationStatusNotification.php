<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NegotiationStatusNotification extends Notification
{
    protected $negotiation;
    protected $status;

    public function __construct($negotiation, $status)
    {
        $this->negotiation = $negotiation;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->status,
            'message' => $this->status === 'accepted'
                ? 'Negosiasi Anda untuk produk "' .
                    $this->negotiation->product->name .
                    '" diterima.'
                : 'Negosiasi Anda untuk produk "' .
                    $this->negotiation->product->name .
                    '" ditolak.',
            'product_id' => $this->negotiation->product_id,
        ];
    }
}


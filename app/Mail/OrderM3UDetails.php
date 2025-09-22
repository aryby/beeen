<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderM3UDetails extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos identifiants IPTV - Commande ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-m3u-details',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->customer_name,
                'orderNumber' => $this->order->order_number,
                'iptvCode' => $this->order->iptv_code,
                'm3uUrl' => $this->order->m3u_url,
                'm3uUsername' => $this->order->m3u_username,
                'm3uPassword' => $this->order->m3u_password,
                'm3uServerUrl' => $this->order->m3u_server_url,
                'expiresAt' => $this->order->expires_at,
                'subscriptionName' => $this->order->subscription ? $this->order->subscription->name : 'Pack Revendeur',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

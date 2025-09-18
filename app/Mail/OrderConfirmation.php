<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre commande IPTV - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'subscription' => $this->order->subscription,
                'customer_name' => $this->order->customer_name,
                'iptv_code' => $this->order->iptv_code,
                'expires_at' => $this->order->expires_at,
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
        $attachments = [];

        // TODO: Ajouter la facture PDF en pièce jointe
        // if ($this->order->isPaid()) {
        //     $attachments[] = Attachment::fromPath($this->generateInvoicePdf())
        //         ->as('facture-' . $this->order->order_number . '.pdf')
        //         ->withMime('application/pdf');
        // }

        return $attachments;
    }
}
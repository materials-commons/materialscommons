<?php

namespace App\Mail\Globus;

use App\Models\GlobusDrop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DropAvailableForUseMail extends Mailable
{
    use Queueable, SerializesModels;

    public GlobusDrop $drop;
    /**
     * Create a new message instance.
     */
    public function __construct(GlobusDrop $drop)
    {
        $this->drop = $drop;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Globus Drop '{$this->drop->name}' available for uploads",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.globus.drops.available',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

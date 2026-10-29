<?php

namespace App\Mail\Globus;

use App\Models\GlobusDrop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DropReadyForImportMail extends Mailable
{
    use Queueable, SerializesModels;

    public GlobusDrop $drop;
    public $userMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(GlobusDrop $drop, $userMessage)
    {
        $this->drop = $drop;
        $this->userMessage = $userMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Drop '{{$this->drop->name}}' Ready For Import",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.globus.drops.ready-for-import',
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

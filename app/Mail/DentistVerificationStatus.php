<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DentistVerificationStatus extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $status;
    protected $notes;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $status, ?string $notes = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->notes = $notes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Actualización del Estado de tu Perfil Profesional',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dentist-verification-status',
            with: [
                'name' => $this->user->name,
                'status' => $this->status,
                'notes' => $this->notes,
            ],
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

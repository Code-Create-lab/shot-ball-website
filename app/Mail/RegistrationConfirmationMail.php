<?php

namespace App\Mail;

use App\Models\Registration;
use App\Services\CertificateGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Goal Shot Ball Association registration is received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration.confirmation',
            with: ['registration' => $this->registration],
        );
    }

    public function attachments(): array
    {
        $generator = app(CertificateGenerator::class);
        $pdf       = $generator->make($this->registration);

        return [
            Attachment::fromData(fn () => $pdf->output(), $generator->filename($this->registration))
                ->withMime('application/pdf'),
        ];
    }
}

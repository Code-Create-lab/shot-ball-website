<?php

namespace App\Jobs;

use App\Mail\RegistrationAdminMail;
use App\Mail\RegistrationConfirmationMail;
use App\Models\Registration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRegistrationEmails implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Seconds to wait before retrying.
     */
    public int $backoff = 30;

    public function __construct(public Registration $registration)
    {
    }

    public function handle(): void
    {
        // Confirmation to the registrant.
        Mail::to($this->registration->email)
            ->send(new RegistrationConfirmationMail($this->registration));

        // Mark the registrant mail as delivered to the mailer.
        $this->registration->update(['email_status' => 'Sent']);

        // Notification to the association admin.
        $admin = config('mail.admin_address');
        if ($admin) {
            Mail::to($admin)
                ->send(new RegistrationAdminMail($this->registration));
        }
    }

    /**
     * Record a failure after all retries are exhausted.
     */
    public function failed(\Throwable $e): void
    {
        $this->registration->update(['email_status' => 'Failed']);
    }
}

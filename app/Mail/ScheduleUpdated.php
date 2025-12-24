<?php

namespace App\Mail;

use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ScheduleSession $session,
        public User $recipient
    ) {
    }

    public function envelope(): Envelope
    {
        $date = $this->session->start_at->translatedFormat('d M Y');

        return new Envelope(
            subject: "📝 Jadwal Diperbarui - {$this->session->title} ({$date})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.schedule-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

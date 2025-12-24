<?php

namespace App\Mail;

use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleCreated extends Mailable
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
            subject: "📚 Jadwal Baru - {$this->session->title} ({$date})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.schedule-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

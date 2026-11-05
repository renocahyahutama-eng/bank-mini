<?php

namespace App\Mail;

use App\Models\Nasabah;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NasabahOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public Nasabah $nasabah;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, Nasabah $nasabah)
    {
        $this->otp = $otp;
        $this->nasabah = $nasabah;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Password — Bank Mini SMKN 1 Bantul',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nasabah_otp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}


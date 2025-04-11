<?php

namespace App\Mail;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlayerInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $player;

    protected $token;

    /**
     * Create a new message instance.
     */
    public function __construct(Player $player, string $token)
    {
        $this->player = $player;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Player Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $this->player->load('club');

        return new Content(
            view: 'emails.player_invitation',
            text: 'emails.player_invitation-text',
            with: [
                'player' => $this->player,
                'token' => $this->token,
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

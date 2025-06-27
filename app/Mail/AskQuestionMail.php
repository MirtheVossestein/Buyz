<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class AskQuestionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $question;
    public $sender;

    /**
     * Create a new message instance.
     */
    public function __construct(string $question, User $sender)
    {
        $this->question = $question;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nieuwe vraag over je advertentie')
                    ->view('emails.ask-question')
                    ->with([
                        'question' => $this->question,
                        'sender' => $this->sender,
                    ]);
    }
}

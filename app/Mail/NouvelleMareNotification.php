<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelleMareNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mare;
    public $fiche;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mare, $fiche)
    {
        $this->mare = $mare;
        $this->fiche = $fiche;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name').' - Nouvelle mare créée')->view('emails.nouvelle_mare_notification');
    }
}

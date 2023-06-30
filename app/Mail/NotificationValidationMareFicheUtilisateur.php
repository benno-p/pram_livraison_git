<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationValidationMareFicheUtilisateur extends Mailable
{
    use Queueable, SerializesModels;

    public $mare;
    public $fiche;
    public $validation;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mare, $fiche, $validation, $type)
    {
        $this->mare = $mare;
        $this->fiche = $fiche;
        $this->validation = $validation;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name').' - Validation d\'une '.$this->type)->view('emails.notification_validation_mare_fiche_utilisateur');
    }
}

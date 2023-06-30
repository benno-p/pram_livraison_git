<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelUtilisateur extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($utilisateur, $password=false)
    {
        $this->utilisateur = $utilisateur;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name').' - Votre compte a été créé')->view('emails.nouvel_utilisateur');
    }
}

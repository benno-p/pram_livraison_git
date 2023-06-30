<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaresFichesCreatedInDay extends Mailable
{
    use Queueable, SerializesModels;

    public $mares;
    public $fiches;
    public $mares_fiches_sans_validateur;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mares, $fiches, $mares_fiches_sans_validateur)
    {
        $this->mares = $mares;
        $this->fiches = $fiches;
        $this->mares_fiches_sans_validateur = $mares_fiches_sans_validateur;

        $this->subject = ' - Nouvelles mares et fiches saisies';
        if($this->mares_fiches_sans_validateur === true){
            $this->subject = ' - Nouvelles mares et fiches saisies sans gestionnaires';   
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name').$this->subject)->view('emails.mares_fiches_created_in_day');
    }
}

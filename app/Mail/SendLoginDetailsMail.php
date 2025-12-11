<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendLoginDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @param  string  $plainPassword
     */
    public function __construct(User $user, string $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Welcome to Dresshare — Your Login Details')
            ->view('emails.login_details')
            ;
    }
}

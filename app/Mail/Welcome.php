<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class Welcome extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 
        $user = User::where('email', $this->email)->first();
        $token = $user->email_token;
        $full_name = $user->full_name;
        $account_link = url('/sso-login');
        return $this->subject('Thank you for register Kemedar')
        ->markdown('emails.welcome')->with(['account_link' => $account_link, 'full_name' => $full_name]);
    }
}

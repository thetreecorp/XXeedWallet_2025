<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class EmailOtp extends Mailable
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
        $token = $user->email_code;
        $full_name = $user->first_name . " " . $user->last_name;
     
        return $this->subject('Thank you for register Xeedwallet')
        ->markdown('emails.email_otp')->with(['otp_code' => $token, 'full_name' => $full_name]);
    }
}

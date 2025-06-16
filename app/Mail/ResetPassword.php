<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\EmailSetting;
class ResetPassword extends Mailable
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
        // get email desc and content email here
        
        $user = User::where('email', $this->email)->first();
        $token = $user->reset_password_token;
        // get link confirm
        $url = url('/') . '/forgot-password/' . $token;
        
        $full_name = $user->full_name;
        //var_dump($contentEmail);exit();
        return $this->subject('Reset Password Kemedar')
        ->markdown('emails.reset_password')->with(['url' => $url, 'full_name' => $full_name, 'token' => $token]);
    }
}

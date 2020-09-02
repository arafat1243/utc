<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewRegistarMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $imageUrl = '';
    public $name = '';
    public $url = '';
    public $email = '';
    public $password = '';
    public function __construct($user = ['name'=> '','email' => '','password'=> ''])
    {
        $this->imageUrl = route('private.assets',str_replace('/',':','images/others/unnamed.png'));
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->url = URL::to('/').'/new';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Complete your profile.')
                    ->from('utcworld@utcworld.net')
                    ->markdown('mail.newRegistar');
    }
}

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $ret = false;
            foreach($event->user->roles as $role){
                if($role->title === 'student'){
                    $ret = true;
                }
            }
        if($ret){
            
        }else{
            if($event->user->status){ 
                return redirect()->route('admin');
            }else{
                return redirect()->route('users.newUser');
            }
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin',function($user){
            $ret = '';
            foreach($user->roles as $role){
                if($role->title === 'super_administrator'){
                    $ret = true;
                }
            }
            return $ret ? Response::allow()
                : Response::deny('You must be a super administrator.');
        });
        Gate::define('isEmployee',function($user){
            if($user->status === 1){
                return false;
            }
            else{
                $ret = '';
                foreach($user->roles as $role){
                    if($role->title === 'employee'){
                        $ret = true;
                    }
                }
                return $ret ? Response::allow()
                    : Response::deny('You must be a Employee.');
            }
        });
        Gate::define('checkStatus',function($user){
            if($user->status === 1){
                return Response::allow();
            }else{
                Auth::logout();
                return Response::deny('You are not verified by the organization. or Please go back and complete your profile');
            }
            
        });
        Gate::define('canDoIt',function($user, $ability){
            $ret = '';
            foreach($user->roles as $role){
                if($role->title === 'super_administrator'){
                    $ret = true;
                }
            }
            if($ret === true){
                return Response::allow();
            }
            else{
                $ret = '';
                $ability = explode(':',$ability);
                foreach($user->roles as $role){
                    $ret =  array_intersect($role->permissions,$ability);
                }
                return $ret ? Response::allow()
                    : Response::deny('You don\'t have permissions to do this.');
                }
        });
    }
}

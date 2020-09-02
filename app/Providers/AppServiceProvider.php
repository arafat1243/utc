<?php

namespace App\Providers;

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });
        Inertia::share([
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
        ]);
        Inertia::share([
            'menu' => function(){
                if(!Auth::user()){
                    return MenuController::index();
                }
            },
            'baseUrl' => URL::to('/').'/'
        ]);
        Inertia::share('successMessage',function(){
            return session()->get('successMessage') ? session()->get('successMessage') : (array)[];
        });
        Inertia::share('auth', function () {
            if (Auth::user()) {
                return [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'avatar' => route('private.assets',str_replace('/',':',Auth::user()->avatar)),
                    'email' => Auth::user()->email,
                    'roles' => Auth::user()->roles,
                ];
            }
        });
    }
}

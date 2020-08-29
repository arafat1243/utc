<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentProfileController extends Controller
{
    public function profile(){
        $user = User::findOrFail(Auth::user()->id);
        $user->load('student');
        return Inertia::render('student/Profile',compact('user'));
    }
    public function update(){
        $user = User::findOrFail(Auth::user()->id);
        $user->load('student');
        return Inertia::render('student/Profile',compact('user'));
    }
}

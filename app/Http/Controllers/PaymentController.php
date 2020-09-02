<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $response = Gate::inspect('canDoIt','student_view:student_update:student_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        return Inertia::render('admin/student/Payment',compact('student'));
    }
}

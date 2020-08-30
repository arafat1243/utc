<?php

namespace App\Http\Controllers;

use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Throwable;

class StudentDashboardController extends Controller
{
    public function index(){
        return Inertia::render('student/Dashboard');
    }

    public function profile(){
        $user = User::findOrFail(Auth::user()->id);
        $user->load('student');
        return Inertia::render('student/Profile',compact('user'));
    }
    public function update(Request $request , User $user){
        if($user->id === Auth::user()->id){
            $user->load('student');
            try{
                if($user->student->number != $request->number){
                    $validator = Student::where('number',$request->number)
                        ->count();
                    if ($validator > 0) {
                        return redirect()->route('student.profile')
                            ->withErrors(['number'=>['Number is already taken']]);
                    }else{
                        $user->student->number = $request->number;
                    }
                }else if($user->student->emergency_number != $request->emergency_number){
                    $validator = Student::Where('emergency_number',$request->emergency_number)
                        ->count();
                    if ($validator > 0) {
                        return redirect()->route('student.profile')
                            ->withErrors(['number'=>['Emergency Number id already taken']]);
                    }else{
                        $user->student->emergency_number = $request->emergency_number;
                    }
                }
                if($request->password){
                    $user->password = Hash::make($request->password);
                }
                $user->name = $request->name;
                $user->student->mother_name = $request->mother_name;
                $user->student->father_name = $request->father_name;
                $user->student->nationality = $request->nationality;
                $user->student->marital_status = $request->marital_status;
                $user->student->dob = $request->dob;
                $user->student->present_address = $request->present_address;
                $user->student->permanent_address = $request->permanent_address;
                $user->student->profession = $request->profession;
                $user->student->blood_group = $request->blood_group;
                $user->student->institute_name = $request->institute_name;
                if($user->push()){
                    return redirect()->route('student.profile')
                                    ->with('successMessage',['success' => true,'message' => 'Your Info updated successfull']);
                }else{
                    return redirect()->route('student.profile')
                                    ->with('successMessage',['success' => false,'message' => 'There is an error to update your Info.']);
                }
            }catch(Throwable $err){
                return redirect()->route('student.profile')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
            }
        }else{
            return abort(403);
        }
    }

    public function batch(){

    }
}

<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Payment;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Throwable;

class StudentDashboardController extends Controller
{
    public function index(){
        $courses = Student::with(['courses.course' => function($q){
                $q->select('id','title');
            },'courses.payment'])
            ->where('user_id',Auth::user()->id)
            ->first('id');
        $finalcourse = [];
        foreach($courses->courses as $course){
            $tepC = [
                'id' => $course->id,
                'course_id' => $course->course_id,
                'fees' =>  $course->fees,
                'title' => $course->course->title,
                'status' => $course->status,
                'payment' => array(),
                'can' => true
            ];
            $total = 0;
            foreach($course->payment as $payment){
                $total += $payment->amount;
                $total < $course->fees ? $tepC['can'] = true : $tepC['can'] = false;
                array_push($tepC['payment'],[
                    'id' => $payment->id,
                    'created_at' => $payment->created_at,
                    'amount' => $payment->amount,
                    'approve' => $payment->approve,
                    'due' => $course->fees - $total 
                ]);
            }
            array_push($finalcourse,$tepC);
        }
        $courses = $finalcourse;
        return Inertia::render('student/Dashboard',compact('courses'));
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

    public function payment(Request $request){
        try{
            $payment = new Payment([
                'course_id'=> $request->course_id,
                'st_has_co_id'=> $request->id,
                'amount'=> $request->amount,
                'created_at'=> NOW()
            ]);

            if($payment->save()){
                return redirect()->route('student')->with('successMessage',['success' => true,'message' => 'Your request is pending and will verify it soon']);
            }else{
                return redirect()->route('student')->with('successMessage',['success' => true,'message' => 'There is an error to send payment request. please try later']);
            }
        }catch(Throwable $err){
            return redirect()->route('student')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }

    }

    public function delete($id){
        try{
            if(Payment::where('id',$id)->where('approve',false)->delete()){
                return redirect()->route('student')->with('successMessage',['success' => true,'message' => 'Your request successfully canceled']);
            }else{
                return redirect()->route('student')->with('successMessage',['success' => true,'message' => 'There is an error to cancle your request. please try later']);
            }
        }catch(Throwable $err){
            return redirect()->route('student')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    public function batch(){
        $batchs = Batch::withCount('students')
            ->with(['course'=>function($q){
                $q->select('id','title','details','banner_img');
            }])
            ->where('last_at','>', Carbon::now())
            ->get();
        $batches = [];
        foreach($batchs as $batch){
            if($batch->capacity > $batch->students_count){
                array_push($batches,$batch);
            }
        }
        return Inertia::render('student/Batch',compact('batches'));
    }
}

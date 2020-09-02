<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Course;
use App\Payment;
use App\StidentHasBatch;
use App\Student;
use App\StudentHasCourse;
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
        $ids = Auth::user()->student->courses->transform(function($co){
                return $co->course_id;
            })->toArray();
        $batchs = Batch::withCount('students')
            ->with(['course'=>function($q){
                $q->select('id','title','details','banner_img');
            }])
            ->whereHas('course',function($q) use($ids){
                $q->whereNotIn('id',$ids);
            })
            ->whereYear('last_at','>=', Carbon::now()->year)
            ->whereMonth('last_at','>=', Carbon::now()->month)
            ->whereDay('last_at','>=', Carbon::now()->day)
            ->get();
        $batches = [];
        foreach($batchs as $batch){
            if($batch->capacity > $batch->students_count){
                $batch->course->banner_img = route('public.assets',str_replace('/',':',$batch->course->banner_img));
                array_push($batches,$batch);
            }
        }
        
        // dd($ids);
        $courses = Course::with(['category'=>function($q){
                $q->select('id','title');
            }])
            ->orderBy('created_at','desc')
            ->whereNotIn('id',$ids)
            ->get()
            ->transform(function($course){
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'details' => $course->details,
                    'details' => $course->details,
                    'class_count' => $course->class_count,
                    'category' => $course->category->title,
                    'class_duration' => $course->class_duration,
                    'course_duration' => $course->course_duration,
                    'banner_img' => route('private.assets',str_replace('/',':',$course->banner_img))
                ];
            })
            ->toArray();
        return Inertia::render('student/Batch',compact('batches','courses'));
    }

    public function details(Request $request, $id){
        $course  = Course::findOrFail($id);
        $course->banner_img = route('private.assets',str_replace('/',':',$course->banner_img));
        $batch_id = '';
        if($request->has('batch_id')){
            $batch_id = $request->batch_id;
        }
        return Inertia::render('student/Details',compact('course','batch_id'));
    }

    public function apply($id,$batch_id = null){
        $course = Course::select('id','title','fees')->findOrFail($id);
        $student_id = Student::select('id')
            ->where('user_id',Auth::user()->id)
            ->first();
        return Inertia::render('student/Apply',compact('course','student_id','batch_id'));
    }

    public function store(Request $request){
        try{
            $batch_id = false;
            if($request->has('batch_id')){
                $batch_id = Batch::withCount('students')
                    ->findOrFail($request->batch_id);
                if($batch_id->capacity < $batch_id->students_count){
                    return true;
                }
            }
            $course = new StudentHasCourse([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'fees' => $request->fees
            ]);
            if($batch_id){
                $batch = new StidentHasBatch([
                    'student_id' => $request->student_id,
                    'batch_id' => $request->batch_id
                ]);
                $batch->save();
            }
            if($course->save()){
                return redirect()->route('student')->with('successMessage',['success' => true,'message' => 'Your request successfully']);
            }else{
                return redirect()->route('student.apply',['course_id'=>$request->course_id,'batch_id'=>$request->batch_id])->with('successMessage',['success' => true,'message' => 'There is an error. please try later']);
            }
        }catch(Throwable $err){
            return redirect()->route('student.apply',['course_id'=>$request->course_id,'batch_id'=>$request->batch_id])->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

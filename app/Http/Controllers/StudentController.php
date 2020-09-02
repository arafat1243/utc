<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Course;
use App\Mail\NewStudentMail;
use App\Payment;
use App\Role;
use App\StidentHasBatch;
use App\Student;
use App\StudentHasCourse;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Throwable;

use function PHPSTORM_META\map;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Gate::inspect('canDoIt','student_view:student_update:student_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $students = Student::orderBy('id','desc')->with(['user','courses.course'=>function($qu){
            $qu->select('id','title');
        },'courses.payment'])->paginate(10);
        // dd($students);
        $students->data = $students->getCollection()->transform(function($student){
            return [
                'id' => $student->id,
                'user_id' => $student->user_id,
                'mother_name' => $student->mother_name,
                'father_name' => $student->father_name,
                'nationality' => $student->nationality,
                'marital_status' => $student->marital_status,
                'gender' => $student->gender,
                'number' => $student->number,
                'emergency_number' => $student->emergency_number,
                'present_address' => $student->present_address,
                'permanent_address' => $student->permanent_address,
                'profession' => $student->profession,
                'blood_group' => $student->blood_group,
                'institute_name' => $student->institute_name,
                'academic_status' => $student->academic_status,
                'registar_at' => $student->created_at,
                'user' => [
                    'id' => $student->user->id,
                    'avatar' => route('private.assets',str_replace('/',':',$student->user->avatar)),
                    'name' => $student->user->name,
                    'email' => $student->user->email,
                    'status' => $student->user->status,
                ],
                'courses' => $student->courses
            ];
        });
        return Inertia::render('admin/student/Index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id,$batch_id = null)
    {
        $course = Course::where('id',$id)
        ->first(['id','title','course_duration','class_duration','class_count','fees']);
        $img = route('public.assets',str_replace('/',':','images/others/check.svg'));
        $def = route('public.assets',str_replace('/',':','images/users/default.png'));
        return Inertia::render('public/Apply',compact('course','batch_id','img','def'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{    $validator = Validator::make($request->only(['email','number','emergency_number']),[
                'email' => 'required|email:rfc,dns|unique:users',
                'number' => 'required|unique:students',
                'emergency_number' => 'required|unique:students',
            ]);
            if ($validator->fails()) {
                return  redirect()->route('public.apply.create',['courseId'=>$request->courseId,'batchId'=>$request->batch_id])
                            ->withErrors($validator);
            }
            $batch_id = false;
            if($request->has('batch_id')){
                $batch_id = Batch::withCount('students')
                    ->findOrFail($request->batch_id);
                if($batch_id->capacity < $batch_id->students_count){
                        return true;
                }
            }
            $path = '';$user = ''; $course = '';$studentRole = 0;
            if ($request->file('avatar')->isValid()) {
                $fileName = 'student-'.(Student::count()+1).'.'.$request->avatar->extension();
                $path = $request->avatar->storeAs('images/student', $fileName,'private');
                if($path){
                    $user = new User([
                        'name' => $request->name,
                        'avatar' => $path,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);
                    
                    $studentRole = Role::where('title','student')->get(['id'])->first();
                    if($user->save()){
                        $data = [
                        'user_id'=>$user->id,
                        'mother_name' => $request->mother_name,
                        'father_name' => $request->father_name,
                        'nationality' => $request->nationality,
                        'marital_status' => $request->marital_status,
                        'dob' => $request->dob,
                        'gender' => $request->gender,
                        'number' => $request->number,
                        'emergency_number' => $request->emergency_number,
                        'present_address' => $request->present_address,
                        'permanent_address' => $request->permanent_address,
                        'profession' => $request->profession,
                        'academic_status' => $request->academic_status,
                        'blood_group' => $request->blood_group || null,
                        'institute_name' => $request->institute_name,
                    ];
                    $student = new Student($data);
                        if($student->save())
                        {
                            $course = new StudentHasCourse([
                                'student_id' => $student->id,
                                'course_id' => $request->courseId,
                                'fees' => $request->fees
                            ]);
                            if($batch_id){
                                $batch = new StidentHasBatch([
                                    'student_id' => $student->id,
                                    'batch_id' => $request->batch_id
                                ]);
                                $batch->save();
                            }
                            $course->save();
                            $user->roles()->sync($studentRole);
                            return  redirect()->route('public.apply.create',['courseId'=>$request->courseId,'batchId'=>$request->batch_id])->with('successMessage',['success' => true,'message' => 'fg']);
                        }
                        
                    }else{
                        return redirect()->route('public.apply.create',['courseId'=>$request->courseId,'batchId'=>$request->batch_id])->with('successMessage',['success' => false,'message' => 'There is some error. Please try again later.']);
                    }
                }
            }
        }catch(Throwable $err){
            if($path){
                Storage::delete('public/'.$path);
                $user->roles()->sync($studentRole);
                $user->delete();
                if($course){
                    $course->delete();
                }
            }
            return redirect()->route('public.apply.create',['courseId'=>$request->courseId,'batchId'=>$request->batch_id])->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($student)
    {
        return Inertia::render('public/Apply');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Student $student)
    {
        $response = Gate::inspect('canDoIt','student_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $student->load(['user']);
        if($request->has('has_id')){
            $student->courses = StudentHasCourse::with(['course'=>function($q){
                    $q->select('id','title','banner_img');
                }])
                ->where('id',$request->has_id)
                ->first();
            $student->courses->course->banner_img = route('private.assets',str_replace('/',':',$student->courses->course->banner_img));
        }else{
            return abort(404);
        }
        $pay_id = null;
        if($request->has('pay_id')){
            $pay_id = $request->pay_id;
        }
        $student->user->avatar = route('private.assets',str_replace('/',':',$student->user->avatar)); 
        if($student->user->status){
            return Inertia::render('admin/student/Course',compact('student','pay_id'));
        } 
        return Inertia::render('admin/student/NewStudent',compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $response = Gate::inspect('canDoIt','student_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $student->load(['user']);
            if($student->user->status){
                if($request->has('has_id') && $request->has('pay_id')){
                    $payment = Payment::where('id',$request->pay_id)->first();
                    $payment->approve = true;
                    if($payment->save()){
                        return redirect()->route('students.index');
                    }
                }else if($request->has('has_id')){
                    $has = StudentHasCourse::where('id',$request->has_id)->first();
                    $has->status = 'ongoing';
                    if($has->save() && Payment::insert([
                        'course_id'=> $has->course_id,
                        'st_has_co_id'=> $has->id,
                        'amount'=> $request->fees,
                        'approve'=> true,
                        'created_at'=> NOW()
                    ])){
                        return redirect()->route('students.index');
                    }
                }else{
                    return abort(404);
                }
            }else{
                $student->load('courses.course');
                if($student->courses()->update(['status'=>'ongoing']) && 
                Payment::insert([
                    'course_id'=> $student->courses[0]->course->id,
                    'st_has_co_id'=> $student->courses[0]->id,
                    'amount'=> $request->fees,
                    'approve'=> true,
                    'created_at'=> NOW()
                ]) && $student->user()->update(['status'=>true])){
                    Mail::to($student->user->email)->send(new NewStudentMail([
                        'name'=>$student->user->name,
                        'course'=>$student->courses[0]->course->title,
                        'image'=> route('public.assets',str_replace('/',':',$student->courses[0]->course->banner_img)),
                        'email' => $student->user->email
                    ]));
                    return redirect()->route('students.index');
                }
            }
        }catch(Throwable $err){
            dd($err->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Student $student)
    {
        $response = Gate::inspect('canDoIt','student_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $student->load('user');
            if($student->user->status){
                if($request->has('has_id') && $request->has('pay_id')){
                    Payment::where('id',$request->pay_id)->delete();
                }else if($request->has('has_id')){
                    StudentHasCourse::where('id',$request->has_id)->delete();
                }
            }else{
                if(Storage::disk('private')->delete($student->user->avatar)){
                    User::where('id',$student->user_id)->delete();
                    StudentHasCourse::where('student_id',$student->id)->delete();
                    DB::table('role_user')->where('user_id', $student->user_id)->delete();
                    $student->delete();
                }
            }
            return redirect()->route('students.index');
        }catch(Throwable $err){
            dd($err->getMessage());
        }
    }

    // Student Has Course

    public function course(Request $request){
        $response = Gate::inspect('canDoIt','student_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if ($request->file('attachment')->isValid()) {
                $fileName = 'student-'.$request->student_id.'-'.$request->course_id.'.'.$request->attachment->extension();
                $path = $request->attachment->storeAs('student/attachment', $fileName,'private');
                if($path){
                    $course = StudentHasCourse::where('student_id',$request->student_id)
                            ->where('course_id',$request->course_id)->first();
                    $course->status = 'complete';
                    $course->attachment = $path;
                    if($course->save()){
                        return redirect()->route('students.index')->with('successMessage',['success' => true,'message' => 'Attachment added successfull']);
                    }
                    else{
                        return redirect()->route('students.index')->with('successMessage',['success' => false,'message' => 'Attachment added Failed']);
                    }
                }
            }
        }catch(Throwable $err){
            if($path){
                Storage::disk('private')->delete($path);
            }
            return redirect()->route('students.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    public function payment(){
        $response = Gate::inspect('canDoIt','student_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $payments = Payment::with(['course'=>function($query){
                $query->select('id','title');
            }])
            ->orderBy('created_at','desc')
            ->where('approve',true)
            ->paginate(10);
            $payments->data = $payments->getCollection()->transform(function($payment,$i){
                return [
                    'serial' => $i+1,
                    'course_name' => $payment->course->title,
                    'payment_at' => $payment->created_at,
                    'status' => $payment->approve,
                    'amount' => $payment->amount
                ];
            })
            ->toArray();
        return Inertia::render('admin/student/Payment',compact('payments'));
    }
}

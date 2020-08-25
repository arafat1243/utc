<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use App\Payment;
use App\Role;
use App\Student;
use App\StudentHasCourse;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $response = Gate::inspect('canDoIt','student_view:student_create:student_update:student_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $students = Student::orderBy('id','desc')->with(['user','courses.course','courses.payment'])->paginate(10);
        return Inertia::render('admin/student/Index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $categorys = CourseCategory::with(['courses'])->get(['id','title'])->map(function($category){
            return [
                'text' => $category->title,
                'value' => $category->id,
                'courses' => $category->courses->map(function($course){
                    return [
                    'text'=>$course->title,
                    'value'=> $course->id,
                    'course_duration'=>$course->course_duration,
                    'class_duration'=>$course->class_duration,
                    'class_count'=>$course->class_count,
                    'fees'=>$course->fees,
                    'category'=> $course->category->id
                ];
                })
            ];
        });
        $course = [];
        if($id){
            $course = Course::with('category')->where('id',$id)->get()->map(function($course){
                return [
                    'text'=>$course->title,
                    'value'=> $course->id,
                    'course_duration'=>$course->course_duration,
                    'class_duration'=>$course->class_duration,
                    'class_count'=>$course->class_count,
                    'fees'=>$course->fees,
                    'category'=> $course->category->id
                ];
            });
        }
        return Inertia::render('public/Apply',compact(['categorys','course']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            // dd($request);
            $validator = Validator::make($request->only(['email','number','emergency_number']),[
                'email' => 'required|email:rfc,dns|unique:users',
                'number' => 'required|unique:students',
                'emergency_number' => 'required|unique:students',
            ]);
            if ($validator->fails()) {
                return redirect()->route('public.apply.create')
                            ->withErrors($validator);
            }
            $path = '';
            if ($request->file('avatar')->isValid()) {
                $fileName = 'student-'.(Student::count()+1).'.'.$request->avatar->extension();
                $path = $request->avatar->storeAs('images/student', $fileName,'public');
                if($path){
                    $user = new User([
                        'name' => $request->name,
                        'avatar' => 'storage/'.$path,
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
                            $course = new StudentHasCourse();
                            $course->insert([
                                'student_id' => $student->id,
                                'course_id' => $request->courseId,
                                'fees' => $request->fees
                            ]);
                            $user->roles()->sync($studentRole);
                            return  redirect()->route('public.apply.create')->with('successMessage',['success' => true,'message' => 'fg']);
                        }
                        
                    }else{
                        return redirect()->route('public.apply.create')->with('successMessage',['success' => false,'message' => 'There is some error. Please try again later.']);
                    }
                }
            }
        }catch(Throwable $err){
            if($path){
                Storage::delete('public/'.$path);
                $user->delete();
                $student->delete();
            }
            return redirect()->route('public.apply.create')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return Inertia::render('public/Apply');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $response = Gate::inspect('canDoIt','student_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $student->load(['user','courses.course']);
        if($student->user->status){
            return abort(404);
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
            $student->load(['user','courses']);
            if($student->user->status){

            }else{
                if($student->courses()->update(['status'=>'ongoing']) && 
                Payment::insert(['course_id'=> $student->courses[0]->id,
                    'amount'=> $request->fees,
                    'approve'=> true,
                    'created_at'=> NOW()
                ]) && $student->user()->update(['status'=>true])){
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
    public function destroy(Student $student)
    {
        $response = Gate::inspect('canDoIt','student_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $file = str_replace('storage','public',$student->user->avatar);
            if(Storage::delete($file)){
                User::where('id',$student->user_id)->delete();
                StudentHasCourse::where('student_id',$student->id)->delete();
                DB::table('role_user')->where('user_id', $student->user_id)->delete();
                $student->delete();
                return redirect()->route('students.index');
            }
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
                $path = $request->attachment->storeAs('images/attachment', $fileName,'public');
                if($path){
                    $course = StudentHasCourse::where('student_id',$request->student_id)
                            ->where('course_id',$request->course_id)
                            ->update(['status'=> 'complete','attachment'=> 'storage/'.$path]);
                    if($course){
                        return redirect()->route('students.index')->with('successMessage',['success' => true,'message' => 'Attachment added successfull']);
                    }
                    else{
                        return redirect()->route('students.index')->with('successMessage',['success' => false,'message' => 'Attachment added Failed']);
                    }
                }
            }
        }catch(Throwable $err){
            if($path){
                Storage::delete('public/'.$path);
            }
            return redirect()->route('students.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

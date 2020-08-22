<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use App\Role;
use App\Student;
use App\StudentHasCourse;
use App\User;
use Illuminate\Http\Request;
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
        //
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
                'courses' => $category->courses
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
            $validator = Validator::make($request->only(['email']),[
                'email' => 'required|email:rfc,dns|unique:users',
            ]);
            if ($validator->fails()) {
                return redirect()->route('public.apply.create')
                            ->withErrors($validator);
            }
            
            if ($request->file('avatar')->isValid()) {
                $fileName = 'student'.(Student::count()+1).'.'.$request->avatar->extension();
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
                            return  redirect()->route('public.apply.create')->with('successMessage',['success' => true,'message' => '']);
                        }
                        
                    }else{
                        return redirect()->route('public.apply.create')->with('successMessage',['success' => false,'message' => 'There is some error. Please try again later.']);
                    }
                }
            }
        }catch(Throwable $err){
            Storage::delete('public/'.$path);
            $user->delete();
            $student->delete();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class CourseControllar extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Gate::inspect('canDoIt','course_view:course_update:course_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $courses = Course::orderBy('id','desc')->with('category')->paginate(10);
        $courses->data = $courses->getCollection()->transform(function($course){
            return [
                'id' => $course->id,
                'title' => $course->title,
                'fees' => $course->fees,
                'details' => $course->details,
                'content' => $course->content,
                'banner_img' => route('private.assets',str_replace('/',':',$course->banner_img)),
                'course_duration' => $course->course_duration,
                'class_duration' => $course->class_duration,
                'class_count' => $course->class_count,
                'cat_id' => $course->cat_id,
                'created_at' => $course->created_at,
                'created_at' => $course->created_at,
                'category' => $course->category,
            ];
        });
        return Inertia::render('admin/course/Index',['courses'=>$courses]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('canDoIt','course_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $categories = array();
        foreach(CourseCategory::orderby('id','desc')->get() as $category){
           array_push($categories,array('text'=>$category->title,'value'=>$category->id));
        }
        return Inertia::render('admin/course/Create',['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','course_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if ($request->file('banner_img')->isValid()) {
                $fileName = 'courses'.(Course::count()+1).'.'.$request->banner_img->extension();
                $path = $request->banner_img->storeAs('images/courses', $fileName,'private');
                if($path){
                    $data = [
                        'title' => $request->title,
                        'fees' => $request->fees,
                        'details' => $request->details,
                        'content' => $request->content,
                        'banner_img' => $path,
                        'course_duration' => $request->course_duration,
                        'class_duration' => $request->class_duration,
                        'class_count' => $request->class_count,
                        'cat_id' => $request->cat_id,
                    ];
                    $course = new Course($data);
                    if($course->save()){
                        return  redirect()->route('courses.create')->with('successMessage',['success' => true,'message' => 'Course added successfully']);
                    }else{
                        return redirect()->route('courses.create')->with('successMessage',['success' => false,'message' => 'There is some error to store this course']);
                    }
                }
            }
        }catch(Throwable $err){
            return redirect()->route('courses.create')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $response = Gate::inspect('canDoIt','course_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $categories = array();
        $course->banner_img = route('private.assets',str_replace('/',':',$course->banner_img));
        foreach(CourseCategory::orderby('id','desc')->get() as $category){
           array_push($categories,array('text'=>$category->title,'value'=>$category->id));
        }
        return Inertia::render('admin/course/Edit',compact('course','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $response = Gate::inspect('canDoIt','course_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
        $path = '';
        if ($request->hasFile('banner_img')) {
            Storage::disk('private')->delete($course->banner_img);
            $fileName = 'courses'.$course->id.'.'.$request->banner_img->extension();
            $path = $request->banner_img->storeAs('images/courses', $fileName,'private');
        }
        else{
            $path = $course->banner_img;
        }
        if($path){
            $course->title = $request->title;
            $course->fees = $request->fees;
            $course->details = $request->details;
            $course->content = $request->content;
            $course->banner_img = $path;
            $course->course_duration = $request->course_duration;
            $course->class_duration = $request->class_duration;
            $course->class_count = $request->class_count;
            $course->cat_id = $request->cat_id;
                if($course->save()){
                    return  redirect()->route('courses.index')->with('successMessage',['success' => true,'message' => 'Course Updated successfully']);
                }else{
                   return redirect()->route('courses.edit')->with('successMessage',['success' => false,'message' => 'There is some error to Update this course']);
                }
            }
        }catch(Throwable $err){
            return redirect()->route('courses.edit')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}

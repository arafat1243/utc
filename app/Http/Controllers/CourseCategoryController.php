<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class CourseCategoryController extends Controller
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
        
        $response = Gate::inspect('canDoIt','course_cate_view:course_cate_create:course_cate_update:course_cate_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $courseCategory = CourseCategory::orderBy('id','desc')->withCount('courses')->paginate(10);
        return Inertia::render('admin/courseCategory/Index',compact('courseCategory'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $response = Gate::inspect('canDoIt','course_cate_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $data = [
                'title' => $request->title,
                'slug' => $request->title,
            ];
            $category = new CourseCategory($data);
            if($category->save()){
                return redirect()->route('courseCategories.index')->with('successMessage',['success' => true,'message' => 'Category added successfully']);
            }
            else{
                return redirect()->route('courseCategories.index')->with('successMessage',['success' => false,'message' => 'There is some error to store this category']);
            }
        }catch(Throwable $err){
            return redirect()->route('courseCategories.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $response = Gate::inspect('canDoIt','course_cate_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $category = CourseCategory::find($id);
            if(empty($category)){
                return abort(404,'This not Found');
            }
            $category->title = $request->title;
            $category->slug = $request->title;
            if($category->save()){
                return redirect()->route('courseCategories.index')->with('successMessage',['success' => true,'message' => 'Category Updated successfully']);
            }
            else{
                return redirect()->route('courseCategories.index')->with('successMessage',['success' => false,'message' => 'There is some error to Update this category']);
            }
        }catch(Throwable $err){
            return redirect()->route('courseCategories.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}

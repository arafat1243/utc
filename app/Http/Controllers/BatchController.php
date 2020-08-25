<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorys = CourseCategory::with('courses')->orderBy('id','desc')->get(['id','title'])->map(function($category){
            return [
                'text' => $category->title,
                'value' => $category->id,
                'courses' => $category->courses->map(function($course){
                    return [
                        'text' => $course->title,
                        'value' => $course->id
                    ];
                })
            ];
        });
        $batches = Batch::with(['course'=>function($qu){
            $qu->select('id','title');
        }]
        )->orderBy('id','desc')
        ->paginate(10);
        $batches->data = $batches->getCollection()->transform(function ($batch) {
            return [
                'id'=> $batch->id,
                'name' =>$batch->course->title,
                'batch_no' => $batch->batch_no,
                'started_at' => $batch->started_at,
                'ended_at' => $batch->ended_at,
                'status' => $batch->ended_at
            ];
        });
        return Inertia::render('admin/batch/Index',compact(['batches','categorys']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $valid = Batch::where('course_id',$request->course_id)->where('batch_no',$request->batch_no)->first();
            if($valid){
                return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => 'This Batch is already exists']);
            }
            $data = [
                'course_id' => $request->course_id,
                'batch_no' => $request->batch_no,
                'started_at' => $request->stated_at,
                'ended_at' => $request->ended_at,
            ];

            $batch = new Batch($data);
            if($batch->save()){
                return redirect()->route('batches.index')->with('successMessage',['success' => true,'message' => 'Batch Added Successfull']);
            }
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => 'Batch Added Failed']);
        }catch(Throwable $err){
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        //
    }
}

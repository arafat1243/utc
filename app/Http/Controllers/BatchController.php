<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        $response = Gate::inspect('canDoIt','batch_view:batch_create:batch_update:batch_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','batch_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $valid = Batch::where('course_id',$request->course_id)
                    ->get(['batch_no'])->transform(function($batch){
                        return $batch->batch_no;
                    });
            $data = [
                'course_id' => $request->course_id,
                'batch_no' => $valid->last()+1,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        $response = Gate::inspect('canDoIt','batch_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $batch->ended_at = $request->ended_at;
            if($batch->save()){
                return redirect()->route('batches.index')->with('successMessage',['success' => true,'message' => 'Batch updated Successfull']);
            }
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => 'Batch updated Failed']);
        }catch(Throwable $err){
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $response = Gate::inspect('canDoIt','batch_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if($batch->delete()){
                return redirect()->route('batches.index')->with('successMessage',['success' => true,'message' => 'Batch Deleted Successfull']);
            }
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => 'Batch Delete Failed']);
        }catch(Throwable $err){
            return redirect()->route('batches.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Course;
use App\Gallery;
use App\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Auth::logout();
        $slides = $this->gallery();
        $gallery = $slides['gallery'];
        $slides = $slides['slides'];
        $popularCourse = $this->populerCourse();
        $batches = $this->upBatch();
        $reviews = Review::where('approved',true)
        ->get(['name','avatar','review'])
        ->transform(function($review){
            return [
                'name' => $review->name,
                'avatar' => route('public.assets',str_replace('/',':',$review->avatar)),
                'review' => $review->review,
            ];
        })
        ->toArray();
        return Inertia::render('public/Home',compact('slides','popularCourse','batches','reviews','gallery'));
    }

    protected function populerCourse(){
        $popularCourse = Batch::with('course')
            ->select(DB::raw('count(*) AS count, course_id'))
            ->whereMonth('created_at','>', Carbon::now()->subMonth(4))
            ->groupBy('course_id')
            ->get()
            ->map(function($batch){
                if($batch->count >= 2){
                    return [
                        'course_id' =>$batch->course_id,
                        'course' => [
                            'id' => $batch->course->id,
                            'title' => $batch->course->title,
                            'details' => $batch->course->details,
                            'banner_img' => route('public.assets',str_replace('/',':',$batch->course->banner_img)),
                            'course_duration' => $batch->course->course_duration,
                            'class_duration' => $batch->course->class_duration,
                            'class_count' => $batch->course->class_count,
                            'cat_id' => $batch->course->cat_id,
                        ],
                    ];
                }
            });
        return $popularCourse;
    }

    protected function upBatch(){
        $batchs = Batch::withCount('students')
            ->with(['course'=>function($q){
                $q->select('id','title','details','banner_img');
            }])
            ->whereYear('last_at','>=', Carbon::now()->year)
            ->whereMonth('last_at','>=', Carbon::now()->month)
            ->whereDay('last_at','>=', Carbon::now()->day)
            ->get();
        $finalBatch = [];
        foreach($batchs as $batch){
            if($batch->capacity > $batch->students_count){
                $batch->course->banner_img = route('public.assets',str_replace('/',':',$batch->course->banner_img));
                array_push($finalBatch,$batch);
            }
        }
        return $finalBatch;
    }

    protected function gallery(){
        $retarnData = [
            'gallery' => array(),
            'slides' => array()
        ];

        $datas = Gallery::orderBy('id','desc')->get(['path','header','paragraph','slide']);

        foreach($datas as $data){
            if($data->slide){
                $data->path = route('public.assets',str_replace('/',':',$data->path));
                array_push($retarnData['slides'],$data);
            }
            array_push($retarnData['gallery'],['name'=>route('public.assets',str_replace('/',':',$data->path))]);
        }
        return $retarnData;
    }
}

<?php

namespace App\Http\Controllers;

use App\Client;
use App\Course;
use App\Gallery;
use App\Payment;
use App\Review;
use App\Service;
use App\Student;
use App\StudentHasCourse;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        
        $slides = Gallery::where('slide',true)
            ->get(['path'])
            ->transform(function($slid){
                return ['path' => route('private.assets',str_replace('/',':',$slid->path))];
            })->toArray(); 
        $totals = $this->total();
        $studentCourse = $this->students();
        $payments = $this->payment();
        return Inertia::render('admin/Dashboard',compact('slides','payments','studentCourse','totals'));
    }
    protected function students(){
        $studentCourse = []; $key = [];
        $students = StudentHasCourse::whereMonth('created_at','>', Carbon::now()->subMonth(3))
            ->withCount('student')
            ->with(['course'=>function($q){
                $q->select('id','title');
            }])
            ->get()
            ->map(function($student){
                return [
                    'count' => $student->student_count,
                    'title' => $student->course->title
                ];
            })->toArray();
            foreach($students as $student => $v){
                if(array_key_exists($v['title'],$key)){
                    $result = $key[$v['title']];
                    $key[$v['title']] = $result + $v['count'];
                }else{
                    $key[$v['title']] = $v['count'];
                }
            }
            foreach($key as $ke => $value){
                array_push($studentCourse,[
                    'x' => $ke,
                    'y' => $value
                ]);
            }
        return $studentCourse;
    }

    protected function payment(){
        $payment = Payment::with(['course'=>function($query){
                $query->select('id','title');
            }])
            ->select(DB::raw('course_id,SUM(amount) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('approve',true)
            ->groupBy('course_id')
            ->get()
            ->map(function($payment){
                return [$payment->course->title, $payment->total];
            });
        $payments = ['label' => [],'series' => []];
        foreach($payment as $pa){
            array_push($payments['label'] ,$pa[0]);
            array_push($payments['series'] ,$pa[1]);
        }
        return $payments;
    }

    protected function total(){
        $student = Student::count();
        $user = User::count() - $student;
        $course = Course::count();
        $review = Review::count();
        $client = Client::count();
        $service = Service::count();
       return [
                [
                    'students',
                    'users',
                    'courses',
                    'reviews',
                    'clients',
                    'services'
                ],
                [
                    $student,
                    $user,
                    $course,
                    $review,
                    $client,
                    $service,
                ]
        ];
    }
}

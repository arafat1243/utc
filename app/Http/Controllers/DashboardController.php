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
        
        $slides = Gallery::where('slide',true)->get(['path']); 
        $totals = $this->total();
        $studentCourse = $this->students();
        $paymentRequest = $this->paymentRequest();
        $payments = $this->payment();
        return Inertia::render('admin/Dashboard',compact('slides','payments','studentCourse','totals','paymentRequest'));
    }

    protected function paymentRequest(){
       return  Payment::with(['student.courses'=>function($q){
                        $q->with(['course'=>function($q){
                            $q->select('id','title');
                        }])->where('status','ongoing');
                    }
                    ,'student.user'=>function($q){
                        $q->select('id','name','avatar');
                    }])
                    ->where('approve',false)
                    ->where('created_at', '>', Carbon::now()->startOfWeek())
                    ->where('created_at', '<', Carbon::now()->endOfWeek())
                    ->get() 
                    ->map(function($payment){
                        return [
                            'id'=>$payment->id,
                            'amount' => $payment->amount,
                            'name'=> $payment->student->user->name,
                            'avatar'=> $payment->student->user->avatar,
                            'number'=> $payment->student->number,
                            'course'=> $payment->student->courses->transform(function($course){
                                return $course->course->title;
                            }),
                        ];
                    });
    }

    protected function students(){
        $studentCourse = [];
        $students = StudentHasCourse::whereMonth('created_at','>', Carbon::now()->subMonth(3))
            ->withCount('student')
            ->with(['course'=>function($q){
                $q->select('id','title');
            }])
            ->get()
            ->map(function($student){
                return [
                    $student->student_count, //$student->student_count
                    $student->course->title
                ];
            });
        $couNam = [];
        foreach($students as $st){
            if(in_array((string) $st[1],$couNam)){
                if (($key = array_search((string) $st[1],$couNam)) !== false) {  
                    $couNam[$st[0]+$key]=$st[1];
                    unset($couNam[$key]);
                }
            }else{
                $couNam[$st[0]]=$st[1];
            }
        }
        foreach($couNam as $key => $value){
            array_push($studentCourse,[
                'x' => $value,
                'y' => $key
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

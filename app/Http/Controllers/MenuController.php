<?php

namespace App\Http\Controllers;

use App\ClientCategory;
use App\CourseCategory;
use App\Service;
class MenuController extends Controller
{
    static public function index(){

        $courses = array();
        $services = array();
        $clients = array();
        foreach(CourseCategory::orderby('id','desc')->get() as $course){
           $courses[$course->id] = ['text'=>$course->title,'path'=>'/courses/'.$course->slug];
        }
        foreach(Service::orderby('id','desc')->get() as $service){
           $services[$service->id] = ['text'=>$service->title,'path'=>'/services/'.$service->slug];
        }
        foreach(ClientCategory::orderby('id','desc')->get() as $client){
           $clients[$client->id] = ['text'=>$client->title,'path'=>'/portfolio/'.$client->slug];
        }
        $menu = [
            ['text' => 'Home', 'icon' => 'mdi-home','path' => '/'],
            ['text' => 'Services', 'icon' => 'mdi-cog','path' => '/services',
                'items' => $services
            ],
            ['text' => 'Course', 'icon' => 'mdi-school','path' => '/courses',
                'items' => $courses
            ],
            ['text' => 'Portfolio', 'icon' => 'mdi-bag-personal','path' => '/portfolio',
                'items' => $clients
            ],
            ['text' => 'Pages', 'icon' => 'mdi-bag-personal','path' => '/pages',
                'items' => [
                    ['text'=> 'Gallery','path'=>'/pages/gallery'],
                    ['text'=> 'Testimonials','path'=>'/pages/testimonials'],
                    ['text'=> 'Write Testimonials','path'=>'/pages/write-testimonials'],
                    ['text'=> 'Student','path'=>'/student','terget'=>'_blank'],
                ]
            ],
            ['text' => 'About', 'icon' => 'mdi-information','path' => '/about',
                'items' => [
                    ['text'=> 'About UTC','path'=>'/about/utc'],
                    ['text'=> 'What We Are','path'=>'/about/what-we-are'],
                    ['text'=> 'Our Vision & Mission','path'=>'/about/our-vision-mission'],
                    ['text'=> 'Our Speciality','path'=>'/about/our-speciality'],
                ]
            ],
        ];
        return $menu;
    }
    
}

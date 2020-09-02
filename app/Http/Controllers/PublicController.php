<?php

namespace App\Http\Controllers;

use App\Client;
use App\Course;
use App\Gallery;
use App\Review;
use App\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;

class PublicController extends Controller
{
    public function clients($slug = null)
    {
        if($slug){
           $clients = Client::orderBy('id','desc')->with('category')->whereHas('category',function($q) use ($slug){
                $q->where('slug',$slug)->orWhere('id',$slug);
           })->get()
           ->transform(function($client){
               return [
                   'id' => $client->id,
                   'title' => $client->title,
                   'details' => $client->details,
                   'avatar' => route('public.assets',str_replace('/',':',$client->avatar)),
               ];
           })->toArray();
            return Inertia::render('public/Clients',compact('clients'));
       }else{
            $clients = Client::orderBy('id','desc')->get()->transform(function($client){
               return [
                   'id' => $client->id,
                   'title' => $client->title,
                   'details' => $client->details,
                   'avatar' => route('public.assets',str_replace('/',':',$client->avatar)),
               ];
           })->toArray();
            return Inertia::render('public/Clients',compact('clients'));
        }
    }

    public function courses($slug = null){
        $allCourse = Course::orderBy('id','desc')
            ->with('category')
            ->get()
            ->transform(function($course){
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'fees' => $course->fees,
                    'details' => $course->details,
                    'banner_img' => route('public.assets',str_replace('/',':',$course->banner_img)),
                    'course_duration' => $course->course_duration,
                    'class_duration' => $course->class_duration,
                    'class_count' => $course->class_count,
                    'category' => $course->category->title,
                ];
            })
            ->toArray();
       if($slug){
           $courses = Course::orderBy('id','desc')->with('category')->whereHas('category',function($q) use ($slug){
                $q->where('slug',$slug)->orWhere('id',$slug);
           })
           ->get()
           ->transform(function($course){
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'fees' => $course->fees,
                    'details' => $course->details,
                    'banner_img' => route('public.assets',str_replace('/',':',$course->banner_img)),
                    'course_duration' => $course->course_duration,
                    'class_duration' => $course->class_duration,
                    'class_count' => $course->class_count,
                    'category' => $course->category->title,
                ];
            })
            ->toArray();
            return Inertia::render('public/Courses',['courses' => $courses,'allCourse' => $allCourse]);
       }
        else{
             return Inertia::render('public/Courses',['courses' => $allCourse,'allCourse' => $allCourse]);
        }
    }

    public function clientForCarasul()
    {
        $clients = [];
        foreach(Client::orderBy('id','desc')->get(['avatar']) as $client){
                    array_push($clients, ['avatar'=>route('public.assets',str_replace('/',':',$client->avatar))]);
        }
        return response()->json(['client' => $clients]);
    }

    public function pages($slug = null){
        if($slug == null || $slug == 'gallery' || $slug == 'testimonials' || $slug == 'write-testimonials'){
            if($slug == null || $slug == 'gallery'){
                $galleries = [];
                foreach(Gallery::orderBy('id','desc')->get(['id','path']) as $name){
                    array_push($galleries, ['name'=>route('public.assets',str_replace('/',':',$name->path)),'id'=>$name->id]);
                }
                return Inertia::render('public/Gallery',['galleries' => $galleries]);
            }
            if($slug == 'testimonials'){
                $reviews = Review::where('approved', 1)
                    ->orderBy('id','desc')
                    ->get(['name','review','avatar'])
                    ->transform(function($review){
                        return [
                            'name' => $review->name,
                            'review' => $review->review,
                            'avatar' => route('public.assets',str_replace('/',':',$review->avatar)),
                        ];
                    })
                    ->toArray(); 
                return Inertia::render('public/Testimonials',['reviews' => $reviews]);
            }
            if($slug == 'write-testimonials'){
                return Inertia::render('public/WriteTestimonials');
            }
        }
        else{
            return abort(404);
        }
    }
    public function about($slug = null){
        if($slug == null || $slug == 'utc' || $slug == 'what-we-are' || $slug == 'our-vision-mission' || $slug == 'our-speciality'){
            if($slug == null || $slug == 'utc'){
                $banner = route('public.assets',str_replace('/',':','images/others/banner.jpg'));
                return Inertia::render('public/AboutUtc',compact('banner'));
            }
            if($slug == 'what-we-are'){
                $banner = route('public.assets',str_replace('/',':','images/others/whatWeAre.jpg'));
                return Inertia::render('public/WhatWeAre',compact('banner'));
            }
            if($slug == 'our-vision-mission'){
                $banner = route('public.assets',str_replace('/',':','images/others/vision-and-mission.jpg'));
                return Inertia::render('public/OurVisionMission',compact('banner'));
            }
            if($slug == 'our-speciality'){
                $banner = route('public.assets',str_replace('/',':','images/others/speciality.jpg'));
                return Inertia::render('public/OurSpeciality',compact('banner'));
            }
        }
        else{
            return abort(404);
        }
    }

    public function services($slug = null){
       if($slug || $slug == null){
           $service = [];
           if($slug == null){
               $service = Service::first();
               $service->banner_img = route('public.assets',str_replace('/',':',$service->banner_img));
           }
           else{
               $service = Service::where('slug',$slug)->first();
               $service->banner_img = route('public.assets',str_replace('/',':',$service->banner_img));
           }
           if(!empty($service)){
                return Inertia::render('public/Services',['service' => $service]);
           }
            return abort(404,'Not Found');
       }
    }

    public function details(Request $request, $id){
        $course  = Course::findOrFail($id);
        $course->banner_img = route('public.assets',str_replace('/',':',$course->banner_img));
        $batch_id = '';
        if($request->has('batch_id')){
            $batch_id = $request->batch_id;
        }
        return Inertia::render('public/Details',compact('course','batch_id'));
    }
}

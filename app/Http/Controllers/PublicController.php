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
           })->get()->toArray();
            return Inertia::render('public/Clients',compact('clients'));
       }else{
            $clients = Client::orderBy('id','desc')->get()->toArray();
            if(!$clients){
                $clients = [];
            }
            return Inertia::render('public/Clients',compact('clients'));
        }
    }

    public function courses($slug = null){
        $allCourse = Course::orderBy('id','desc')->with('category')->get()->toArray();
       if($slug){
           $courses = Course::orderBy('id','desc')->with('category')->whereHas('category',function($q) use ($slug){
                $q->where('slug',$slug)->orWhere('id',$slug);
           })->get()->toArray();
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
                    array_push($clients, ['avatar'=>Url::to('/').'/'.$client->avatar]);
        }
        return response()->json(['client' => $clients]);
    }

    public function pages($slug = null){
        if($slug == null || $slug == 'gallery' || $slug == 'testimonials' || $slug == 'write-testimonials'){
            if($slug == null || $slug == 'gallery'){
                $galleries = [];
                foreach(Gallery::orderBy('id','desc')->get(['id','path']) as $name){
                    array_push($galleries, ['name'=>Url::to('/').'/'.$name->path,'id'=>$name->id]);
                }
                return Inertia::render('public/Gallery',['galleries' => $galleries]);
            }
            if($slug == 'testimonials'){
                $reviews = Review::where('approved', 1)->orderBy('id','desc')->get(['name','review','avatar'])->toArray(); 
                return Inertia::render('public/Testimonials',['reviews' => $reviews]);
            }
            if($slug == 'write-testimonials'){
                return Inertia::render('public/WriteTestimonials');
            }
        }
        else{
            return abort(404,'This course is not found');
        }
    }
    public function about($slug = null){
        if($slug == null || $slug == 'utc' || $slug == 'what-we-are' || $slug == 'our-vision-mission' || $slug == 'our-speciality'){
            if($slug == null || $slug == 'utc'){

                return Inertia::render('public/AboutUtc');
            }
            if($slug == 'what-we-are'){
                return Inertia::render('public/WhatWeAre');
            }
            if($slug == 'our-vision-mission'){
                return Inertia::render('public/OurVisionMission');
            }
            if($slug == 'our-speciality'){
                return Inertia::render('public/OurSpeciality');
            }
        }
        else{
            return abort(404,'This course is not found');
        }
    }

    public function services($slug = null){
       if($slug || $slug == null){
           $service = [];
           if($slug == null){
               $service = Service::first();
           }
           else{
               $service = Service::where('slug',$slug)->first();
           }
           if(!empty($service)){
                return Inertia::render('public/Services',['service' => $service]);
           }
            return abort(404,'Not Found');
       }
    }

    public function details(Request $request, $id){
        $course  = Course::findOrFail($id);
        $batch_id = '';
        if($request->has('batch_id')){
            $batch_id = $request->batch_id;
        }
        return Inertia::render('public/Details',compact('course','batch_id'));
    }
}

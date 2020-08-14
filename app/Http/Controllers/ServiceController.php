<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ServiceController extends Controller
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
        
        $response = Gate::inspect('canDoIt','service_view:service_create:service_update:service_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $services = Service::orderBy('id','desc')->paginate(10);
        return Inertia::render('admin/services/Index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('canDoIt','service_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        return Inertia::render('admin/services/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','service_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if ($request->file('banner_img')->isValid()) {
                $fileName = 'services'.(Service::count()+1).'.'.$request->banner_img->extension();
                $path = $request->banner_img->storeAs('images/services', $fileName,'public');
                if($path){
                    $data = [
                        'title' => $request->title,
                        'slug' => $request->title,
                        'details' => $request->details,
                        'banner_img' => 'storage/'.$path,
                    ];
                    // dd($data);
                    $course = new Service($data);
                    if($course->save()){
                        return  redirect()->route('services.create')->with('successMessage',['success' => true,'message' => 'service added successfully']);
                    }else{
                        return redirect()->route('services.create')->with('successMessage',['success' => false,'message' => 'There is some error to store this service']);
                    }
                }
            }
        }catch(Throwable $err){
            // dd($err->getMessage());
            return redirect()->route('services.create')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $response = Gate::inspect('canDoIt','service_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        return Inertia::render('admin/services/Edit',compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $response = Gate::inspect('canDoIt','service_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
        $path = '';
        if ($request->hasFile('banner_img')) {
            $file = str_replace('storage','public',$service->banner_img);
            Storage::delete($file);
                $fileName = 'services'.(Service::count()+1).'.'.$request->banner_img->extension();
                $path = 'storage/'.$request->banner_img->storeAs('images/services', $fileName,'public');
            
        }
        else{
            $path = $service->banner_img;
        }
        if($path){
            $service->title = $request->title;
            $service->slug = $request->title;
            $service->details = $request->details;
            $service->banner_img = $path;
                if($service->save()){
                    return  redirect()->route('services.index')->with('successMessage',['success' => true,'message' => 'Service Updated successfully']);
                }else{
                   return redirect()->route('services.edit')->with('successMessage',['success' => false,'message' => 'There is some error to Update this Service']);
                }
            }
        }catch(Throwable $err){
            return redirect()->route('services.edit')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $response = Gate::inspect('canDoIt','service_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $file = str_replace('storage','public',$service->banner_img);
            if(Storage::delete($file)){
                if($service->delete()){
                    return  redirect()->route('services.index')->with('successMessage',['success' => true,'message' => 'Service Deleted successfully']);
                }
            }
            return  redirect()->route('services.index')->with('successMessage',['success' => false,'message' => 'Unable to delete this Service']);
        }catch(Throwable $err){
            return  redirect()->route('services.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

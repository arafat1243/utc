<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Throwable;

class GalleryController extends Controller
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
        $response = Gate::inspect('canDoIt','gallery_view:gallery_update:gallery_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $photos = Gallery::orderBy('id','desc')->paginate(10);
        return Inertia::render('admin/gallery/Index',compact('photos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','gallery_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $validator = Validator::make($request->only(['photos']),[
                'photos' => 'required|filled',
            ]);
            if ($validator->fails()) {
                return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => 'Photos Must be required']);
            }
            if ($request->hasFile('photos')) {
                $course = false;
                $count = Gallery::count()+1;
                foreach($request->photos as $photo){
                    $fileName = 'gallery'.$count.'.'.$photo->extension();
                    $path = 'storage/'.$photo->storeAs('images/gallery', $fileName,'public');
                    $course = Gallery::create(['path' => $path]);
                    $count++;
                }
                    if($course){
                        return  redirect()->route('gallery.index')->with('successMessage',['success' => true,'message' => 'Photos added successfully']);
                    }else{
                        return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => 'There is some error to store this Photos']);
                    }
            }
        }catch(Throwable $err){
            return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
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
        $response = Gate::inspect('canDoIt','gallery_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $gallery = Gallery::find($id);
            if(empty($gallery)){
                return abort(404,'This not Found');
            }
            $gallery->header = $request->header;
            $gallery->paragraph = $request->paragraph;
            $gallery->slide = $request->slide;
            if($gallery->save()){
                return redirect()->route('gallery.index')->with('successMessage',['success' => true,'message' => 'Slide Updated successfully']);
            }
            else{
                return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => 'There is some error to Update this Slide']);
            }
        }catch(Throwable $err){
            return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Gate::inspect('canDoIt','gallery_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        
        try{
            $photo = Gallery::find($id);
            if(empty($photo)){
                return abort(404,'Not Found');
            }
            $filename = str_replace('storage','public',$photo->path);
            Storage::delete($filename);
            if($photo->delete()){
                return redirect()->route('gallery.index')->with('successMessage',['success' => true,'message' => 'Photo Deleteed successfully']);
            }
            else{
                return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => 'There is some error to Delete this Photo']);
            }
        }catch(Throwable $err){
            return redirect()->route('gallery.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

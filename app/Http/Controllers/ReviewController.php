<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Throwable;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('auth');
        $response = Gate::inspect('canDoIt','course_cate_view:course_cate_create:course_cate_update:course_cate_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $reviews = Review::orderBy('id','desc')->paginate(10);
        return Inertia::render('admin/review/Index',compact('reviews'));
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
            $validator = Validator::make($request->only(['email','number']),[
                'email' => 'required|email:rfc,dns|unique:reviews',
                'number' => 'required|unique:reviews|max:11|min:11',
            ]);
            if ($validator->fails()) {
                return redirect()->route('public.pages','write-testimonials')
                            ->withErrors($validator);
            }
            if ($request->file('avatar')->isValid()) {
                $fileName = 'reviewer'.(Review::count()+1).'.'.$request->avatar->extension();
                $path = $request->avatar->storeAs('images/review', $fileName,'public');
                if($path){
                    $data = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'number' => $request->number,
                        'review' => $request->review,
                        'avatar' => 'storage/'.$path
                    ];
                    $review = new Review($data);
                    if($review->save()){
                        return  redirect()->route('public.pages','write-testimonials')->with('successMessage',['success' => true,'message' => 'Thanks for your Review']);
                    }else{
                        return redirect()->route('public.pages','write-testimonials')->with('successMessage',['success' => false,'message' => 'There is some error to store your review']);
                    }
                }
            }
        }catch(Throwable $err){
            return redirect()->route('public.pages','write-testimonials')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
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
        $response = Gate::inspect('canDoIt','review_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $review = Review::find($id);
            if(empty($review)){
                return abort(404,'This not Found');
            }
            $review->approved = $request->approved;
            if($review->save()){
                return redirect()->route('review.index')->with('successMessage',['success' => true,'message' => 'Review Updated successfully']);
            }
            else{
                return redirect()->route('review.index')->with('successMessage',['success' => false,'message' => 'There is some error to Update this Review']);
            }
        }catch(Throwable $err){
            return redirect()->route('review.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
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
        $response = Gate::inspect('canDoIt','review_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $review = Review::where('id',$id)->first();
            if(empty($review)){
                return abort(404,'This not Found');
            }
            $filename = str_replace('storage','public',$review->avatar);
             Storage::delete($filename);
             
            if($review->delete()){
                return redirect()->route('review.index')->with('successMessage',['success' => true,'message' => 'Review Deleteed successfully']);
            }
            else{
                return redirect()->route('review.index')->with('successMessage',['success' => false,'message' => 'There is some error to Delete this Review']);
            }
        }catch(Throwable $err){
            return redirect()->route('review.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

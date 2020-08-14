<?php

namespace App\Http\Controllers;

use App\ClientCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ClientCategoryController extends Controller
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
        $response = Gate::inspect('canDoIt','client_cate_create:client_cate_view:client_cate_update:client_cate_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $clientCategories = ClientCategory::orderBy('id','desc')->withCount('clients')->paginate(10);
        return Inertia::render('admin/clientCategory/Index',compact('clientCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','client_cate_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $data = [
                'title' => $request->title,
                'slug' => $request->title,
            ];
            $category = new ClientCategory($data);
            if($category->save()){
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => true,'message' => 'Category added successfully']);
            }
            else{
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => 'There is some error to store this category']);
            }
        }catch(Throwable $err){
            return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
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
        $response = Gate::inspect('canDoIt','client_cate_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $category = ClientCategory::find($id);
            if(empty($category)){
                return abort(404,'This not Found');
            }
            $category->title = $request->title;
            $category->slug = $request->title;
            if($category->save()){
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => true,'message' => 'Category Updated successfully']);
            }
            else{
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => 'There is some error to Update this category']);
            }
        }catch(Throwable $err){
            return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
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
        $response = Gate::inspect('canDoIt','course_cate_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $category = ClientCategory::where('id',$id)->with('clients')->first();
            if(empty($category)){
                return abort(404,'This not Found');
            }
            foreach($category->clients as $client){
                $filename = str_replace('storage','public',$client->avatar);
                Storage::delete($filename);
            }
            $category->clients()->delete();
            if($category->delete()){
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => true,'message' => 'Category Deleteed successfully']);
            }
            else{
                return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => 'There is some error to Delete this category']);
            }
        }catch(Throwable $err){
            return redirect()->route('clientCategories.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

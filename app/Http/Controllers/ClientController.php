<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ClientController extends Controller
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
        $response = Gate::inspect('canDoIt','client_view:client_update:client_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $clients = Client::orderBy('id','desc')->with('category')->paginate(10);
        return Inertia::render('admin/client/Index',compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('canDoIt','client_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $categories = [];
        foreach(ClientCategory::orderBy('id','desc')->get(['id','title']) as $category){
           array_push($categories,array('text'=>$category->title,'value'=>$category->id));
        }
        return Inertia::render('admin/client/Create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('canDoIt','client_create');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if ($request->file('banner_img')->isValid()) {
                $fileName = 'clients'.(Client::count()+1).'.'.$request->banner_img->extension();
                $path = $request->banner_img->storeAs('images/clients', $fileName,'public');
                if($path){
                    $data = [
                        'title' => $request->title,
                        'details' => $request->details,
                        'avatar' => 'storage/'.$path,
                        'cat_id' => $request->cat_id,
                    ];
                    $client = new Client($data);
                    if($client->save()){
                        return  redirect()->route('clients.create')->with('successMessage',['success' => true,'message' => 'Client added successfully']);
                    }else{
                        return redirect()->route('clients.create')->with('successMessage',['success' => false,'message' => 'There is some error to store this Client']);
                    }
                }
            }
        }catch(Throwable $err){
            return redirect()->route('clients.create')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $response = Gate::inspect('canDoIt','client_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $categories = [];
        foreach(ClientCategory::orderBy('id','desc')->get(['id','title']) as $category){
           array_push($categories,array('text'=>$category->title,'value'=>$category->id));
        }
        return Inertia::render('admin/client/Edit',compact('categories','client'));
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
        $response = Gate::inspect('canDoIt','client_update');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $client = Client::find($id);
            if(empty($client)){
                return abort(404,'Not Found');
            }
        $path = '';
        if ($request->hasFile('banner_img')) {
            $file = str_replace('storage','public',$client->avatar);
            Storage::delete($file);
                $fileName = 'clients'.(client::count()+1).'.'.$request->banner_img->extension();
                $path = 'storage/'.$request->banner_img->storeAs('images/clients', $fileName,'public');
            
        }
        else{
            $path = $client->avatar;
        }
        if($path){
            $client->title = $request->title;
            $client->cat_id = $request->cat_id;
            $client->details = $request->details;
            $client->avatar = $path;
                if($client->save()){
                    return  redirect()->route('clients.index')->with('successMessage',['success' => true,'message' => 'Client Updated successfully']);
                }else{
                   return redirect()->route('clients.edit')->with('successMessage',['success' => false,'message' => 'There is some error to Update this Client']);
                }
            }
        }catch(Throwable $err){
            return redirect()->route('clients.edit')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $response = Gate::inspect('canDoIt','client_delete');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $file = str_replace('storage','public',$client->avatar);
            if(Storage::delete($file)){
                if($client->delete()){
                    return  redirect()->route('clients.index')->with('successMessage',['success' => true,'message' => 'Client Deleted successfully']);
                }
            }
            return  redirect()->route('clients.index')->with('successMessage',['success' => false,'message' => 'Unable to delete this Client']);
        }catch(Throwable $err){
            return  redirect()->route('clients.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }
}

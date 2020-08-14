<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Throwable;

class RoleController extends Controller
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
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        $roles = Role::orderBy('id','desc')->where([['title', '<>', 'student'],['title','<>','employee'],['title','<>','super_administrator']])->paginate(10);
        return Inertia::render('admin/role/Index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        return Inertia::render('admin/role/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $data = [
                'title' => $request->title,
                'permissions' => $request->permissions
            ];
            $role = new Role($data);
            if($role->save()){
                return redirect()->route('roles.create')->with('successMessage',['success' => true,'message' => 'Role Addded successfully']);
            }
            else{
                return redirect()->route('roles.create')->with('successMessage',['success' => false,'message' => 'There is some error to store this role']);
            }
        }catch(Throwable  $e){
            return redirect()->route('roles.create')->with('successMessage',['success' => false,'message' => $e->getMessage()]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        return Inertia::render('admin/role/Edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $role = Role::find($id);
            if(empty($role)){
                return abort(404,'Not found');
            }
            $role->title = $request->title;
            $role->permissions = $request->permissions;
            if($role->save()){
                return redirect()->route('roles.index')->with('successMessage',['success' => true,'message' => 'Role Updated successfully']);
            }
            else{
                return redirect()->route('roles.edit',$id)->with('successMessage',['success' => false,'message' => 'There is some error to Delete this role']);
            }
        }catch(Throwable  $e){
            return redirect()->route('roles.edit',$id)->with('successMessage',['success' => false,'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            if(count($role->users) > 0){
                DB::table('role_user')->where('role_id', $role->id)->delete();
            }
            if($role->delete()){
                return redirect()->route('roles.index')->with('successMessage',['success' => true,'message' => 'Role Deleted successfully']);
            }
            else{
                return redirect()->route('roles.index')->with('successMessage',['success' => false,'message' => 'There is some error to Delete this role']);
            }
        }catch(Throwable  $e){
            return redirect()->route('roles.index')->with('successMessage',['success' => false,'message' => $e->getMessage()]);
        }
    }
}

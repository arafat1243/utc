<?php

namespace App\Http\Controllers;

use App\Employe;
use App\Mail\NewRegistarMail;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
            $users = User::orderBy('id','desc')->with('roles','employe')->where('id','<>',Auth::user()->id)->whereHas('roles', function($q){
                $q->where([['title', '<>', 'student'],['title','<>','super_administrator']]); 
            })->paginate(10);
            $roles = [];
            foreach(Role::orderBy('id','desc')->where([['title', '<>', 'student'],['title', '<>', 'employee'],['title','<>','super_administrator']])->get(['id','title']) as $role){
                array_push($roles,['value'=>$role->id,'text'=>$role->title]);
            }
            return Inertia::render('admin/user/Index',compact('users','roles'));    
    }
    public function addRoles(Request $request, $id){
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
            try{
            $user = User::find($id);
            if(empty($user)){
            return abort(404,'not Found');
            }
            $user->status = $request->status;
            if($user->save()){
                $role_id =  [];
                foreach(explode(',',$request->role_id) as $id){
                    array_push($role_id,$id);
                }
                $user->roles()->sync($role_id);
               return redirect()->route('users.index')->with('successMessage',['success' => true,'message' => 'Role assigns successfully']);
            }
                return redirect()->route('users.index')->with('successMessage',['success' => false,'message' => 'There is some error to assign those roles']);
            }catch(Throwable $err){
                return redirect()->route('users.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
            }
    }
    public function addUser(Request $request){
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
   try{      $validator = Validator::make($request->only(['email']),[
                'email' => 'required|email:rfc,dns|unique:users',
            ]);
            if ($validator->fails()) {
                return redirect()->route('users.index')
                            ->withErrors($validator);
            }
         $employee = Role::where('title','employee')->get(['id'])->first();
        $role_id = array($employee->id);
        if($request->role_id){
            foreach(explode(',',$request->role_id) as $id){
                array_push($role_id,$id);
            }
        }
        $password = Str::random(16);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => 'storage/images/users/default.png',
            'password' => Hash::make($password),
        ];
        
            $user = new User($data);
            if($user->save()){
                $user->roles()->sync($role_id);
                $user = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $password,
                ];
                Mail::to($request->email)->send(new NewRegistarMail($user));
               return redirect()->route('users.index')->with('successMessage',['success' => true,'message' => 'User Create successfully']);
            }
        }catch(Throwable $err){
            $user->roles()->sync($role_id);
            $user->delete();
            return redirect()->route('users.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $response = Gate::inspect('isAdmin');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $user = User::find($id);
            $user->load('employe');
            if(empty($user)){
                return abort(404,'Not Found');
            }
            if($user->employe){
                $file = str_replace('storage','public',$user->avatar);
                Storage::delete($file);
                $file = str_replace('storage','public',$user->employe->cv);
                Storage::delete($file);
                $user->employe()->delete();
            }
            DB::table('role_user')->where('user_id', $id)->delete();
            if($user->delete()){
                return  redirect()->route('users.index')->with('successMessage',['success' => true,'message' => 'User Deleted successfully']);
            }
        }catch(Throwable $err){
            return redirect()->route('users.index')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

    public function profile(){
        $user = User::findOrFail(Auth::user()->id);
        $user->load(['roles','employe']);
        return Inertia::render('admin/user/Profile',compact('user'));
    }

    public function update(Request $request, User $user){
        if($user->id === Auth::user()->id){
            $user->load('employe');
            try{
                if($user->employe->number != $request->number){
                    $validator = Employe::where('number',$request->number)->count();
                    if ($validator > 0) {
                        return redirect()->route('users.profile')
                                    ->withErrors(['number'=>['This Number is already taken']]);
                    }
                }
                if ($request->hasFile('avatar')) {
                    if(!$user->avatar === 'storage/images/users/default.png'){
                        $file = str_replace('storage','public',$user->avatar);
                        Storage::delete($file);
                    }   
                    $fileName = 'avatar-'.(user::count()+1).'.'.$request->avatar->extension();
                    $user->avatar = 'storage/'.$request->avatar->storeAs('images/users', $fileName,'public');     
                }
                if($request->number){
                    $user->employe->number = $request->number;
                     $user->employe->save();
                }
                if($request->password){
                    $user->password = Hash::make($request->password);
                }
                $user->name = $request->name;
                if($user->save()){
                    return redirect()->route('users.profile')->with('successMessage',['success' => true,'message' => 'Profile Updated Successfully']);
                }

            }catch(Throwable $err){
                return redirect()->route('users.profile')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
            }
        }else{
            return abort(403);
        }
    }
}

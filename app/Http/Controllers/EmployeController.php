<?php

namespace App\Http\Controllers;

use App\Employe;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EmployeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = Gate::inspect('isEmployee');
        if ($response->denied()) {
            return abort(403,$response->message());
        }
        try{
            $validator = Validator::make($request->only(['number']),[
                'number' => 'required|unique:employes',
            ]);
            if ($validator->fails()) {
                return redirect()->route('users.newUser')
                            ->withErrors($validator);
            }
            if($request->hasFile('avatar') && $request->hasFile('cover_Letter')){
                $imagePath = $request->avatar->storeAs('images/users', 'avatar-'.Auth::user()->id.'.'.$request->avatar->extension(),'private');
                $cvPath = $request->cover_Letter->storeAs('cv', 'cover_Letter-'.Auth::user()->id.'.'.$request->cover_Letter->extension(),'private');
                if($imagePath && $cvPath){
                    $employeData = [
                        'user_id' => Auth::user()->id,
                        'number' => $request->number,
                        'cv' => $cvPath
                    ];
                    $employe = new Employe($employeData);
                    $user = User::find(Auth::user()->id);
                    $user->name = $request->name;
                    $user->password = Hash::make($request->password);
                    $user->avatar = $imagePath;
                    $user->status = 1;

                    if($employe->save() && $user->save()){
                        return redirect()->route('admin');
                    }
                }
            }
        }catch(Throwable $err){
            return redirect()->route('users.newUser')->with('successMessage',['success' => false,'message' => $err->getMessage()]);
        }
    }

}

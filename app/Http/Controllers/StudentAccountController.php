<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\NewUserRequest;
use Illuminate\Http\Request;
use Hash;
use Session;
use Storage;

class StudentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('manage.student.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manage.student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewUserRequest $request)
    {
            // check if user is a cordinator
            if (request()->user()->cannot('store', User::class)) {
                Session::flash('error', 'You cannot perform this action as a student');
                return back();
            }

            // Retrieve the validated input data...
            $validated = $request->validated();
    
            // instantiate a new user
            $user = new User();
            $user->name = $validated['name'];
            $user->lastName = $validated['lastName'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->password = Hash::make($validated['password']);
            $user->isCordinator = $validated['isCordinator'];

           
            
            // if an image was uploaded
            if($request->hasfile('photo'))
            {
                $request->file('photo')->store('', 'public');
            }
            $user->save();
    
            Session::flash('success', 'User Saved Successfully');
    
            return redirect()->route('user.index');
            
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // check if user is a cordinator
        if (request()->user()->cannot('view', $user)) {
            Session::flash('error', 'You cannot view this profile as a student');
            return back();
        }
        return view('manage.student.show', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
         
            // $user->update($request->all());
    
            $user->name = $request->get('name');
            $user->lastName = $request->get('lastName');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->isCordinator = $request->get('isCordinator');

        
            // if an image was uploaded
            if($request->hasfile('photo'))
            {
                $request->file('photo')->store('', 'public');
            }
            $user->update();
    
            Session::flash('success', 'User updated Successfully');
    
            return redirect()->route('user.index');
            
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
  
            // check if user is a cordinator
            if (request()->user()->cannot('delete', $user)) {
                Session::flash('error', 'You cannot delete a Cordinator');
                return back();
            }
            $user->delete();
            Session::flash('status', 'User deleted successfully' );
            return redirect()->back();
        
    }
}

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
        try 
        {
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
                $allowedfileExtension=['jpg','png'];
                $file = $request->file('photo'); 
                $errors = [];
                
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
                
                if($check) 
                {
                    $mediaFile = $request->photo;
                    $mediaFileName = $this->getUniqueReference() . '_' . now()->timestamp . '.' . $extension;;
                    $user->photo = 'uploads/'.$mediaFileName;
                    Storage::putFileAs('public/uploads', $mediaFile, $mediaFileName);
                    // $user->update(); 
                }
                // Store File
                // if ($media) 
                // {
                // }
            }
            $user->save();
    
            Session::flash('success', 'User Saved Successfully');
    
            return redirect()->route('user.index');
            
        } 
        catch (\Throwable $th) 
        {
            Session::flash('error', 'Error saving user');
           \Log::error($th);
           return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('manage.student.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        try 
        {
    
            $user->name = $request->get('name');
            $user->lastName = $request->get('lastName');
            $user->email = $request->get('email');
            $user->phone = $request->get('phone');
            $user->isCordinator = $request->get('isCordinator');

        
            // if an image was uploaded
            if($request->hasfile('photo'))
            {
                $allowedfileExtension=['jpg','png'];
                $file = $request->file('photo'); 
                $errors = [];
                
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
                
                if($check) 
                {
                    $mediaFile = $request->photo;
                    $mediaFileName = $this->getUniqueReference() . '_' . now()->timestamp . '.' . $extension;;
                    $user->photo = 'uploads/'.$mediaFileName;
                    Storage::putFileAs('public/uploads', $mediaFile, $mediaFileName);
                    // $user->update(); 
                }
                // Store File
                // if ($media) 
                // {
                // }
            }
            $user->update();
    
            Session::flash('success', 'User updated Successfully');
    
            return redirect()->route('user.index');
            
        } 
        catch (\Throwable $th) 
        {
            Session::flash('error', 'Error updating user');
           \Log::error($th);
           return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try 
        {
            $user->delete();
            Session::flash('status', 'User deleted successfully' );
            return redirect()->back();
        } 
        catch (\Throwable $th) 
        {
            \Log::error($th);
            Session::flash('error', 'Unable to delete user' );
            return back();

            //throw $th;
        }
    }
}

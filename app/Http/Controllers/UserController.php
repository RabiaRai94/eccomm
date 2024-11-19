<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::query();
            return datatables()->of($users)
                ->addColumn('actions', function ($user) {
                    return view('admin.users.partials.actions', compact('user'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.users.index');
    }
  
    public function create()
    {
        return view('admin.users.create', ['user' => null]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phonenumber' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'profile_picture' => 'nullable|file|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $profilePictureId = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');

            $attachment = Attachment::create([
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            $profilePictureId = $attachment->id;
        }


        $user = User::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phonenumber' => $request->input('phonenumber'),
            'address' => $request->input('address'),
            'postal_code' => $request->input('postal_code'),
            'status' => $request->input('status'),
            'profile_picture_id' => $profilePictureId,
        ]);


        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user
        ], 201);
    }
    public function getUsers()
    {
        $users = User::query(); 
        return DataTables::of($users)
           
            ->addColumn('actions', function ($user) {
                return view('admin.users.partials.actions')->render();
            })
            ->rawColumns(['actions']) 
            ->make(true);
    }
    // public function getUsersData()
    // {
    //     $users = User::select(['id', 'firstname', 'lastname', 'email', 'phonenumber', 'status']);  // Specify fields to retrieve

    //     return datatables()->of($users)
    //         ->addColumn('action', function ($user) {
    //             return '
    //                 <a href="' . route('users.show', $user->id) . '" class="btn btn-info btn-sm">View</a>
    //                 <a href="' . route('users.edit', $user->id) . '" class="btn btn-primary btn-sm">Edit</a>
    //                 <button onclick="deleteUser(' . $user->id . ')" class="btn btn-danger btn-sm">Delete</button>
    //             ';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }
   
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }


    public function edit(User $user)
    {
        return view('admin.users.create', compact('user'));
    }

   
    public function update(Request $request, User $user)
    {
     
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phonenumber' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

     
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture_id) {
                $oldAttachment = Attachment::find($user->profile_picture_id);
                Storage::delete($oldAttachment->path); 
                $oldAttachment->delete();
            }

            $path = $request->file('profile_picture')->store('profile_pictures');
            $attachment = Attachment::create(['path' => $path]);
            $user->profile_picture_id = $attachment->id;
        }

       
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    //     public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();

    //     return response()->json(['message' => 'User deleted successfully.']);
    // }

}

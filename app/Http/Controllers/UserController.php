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
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $users = User::all();
    //     return view('admin.users.index', compact('users'));
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::query();
            return datatables()->of($users)
                ->addColumn('actions', function ($user) {
                    return view('admin.partials.actions', compact('user'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.users.index');
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validate all fields
    //     $validator = Validator::make($request->all(), [
    //         'firstname' => 'required|string|max:255',
    //         'lastname' => 'nullable|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6',
    //         'phonenumber' => 'nullable|string|max:20',
    //         'address' => 'nullable|string|max:255',
    //         'postal_code' => 'nullable|string|max:20',
    //         'status' => 'required|in:active,inactive',
    //         'profile_picture' => 'nullable|image|max:2048', // max 2MB image
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Handle profile picture upload
    //     $profilePictureId = null;
    //     if ($request->hasFile('profile_picture')) {
    //         $path = $request->file('profile_picture')->store('profile_pictures');
    //         $attachment = Attachment::create(['path' => $path]);
    //         $profilePictureId = $attachment->id;
    //     }

    //     // Create the user
    //     $user = User::create([
    //         'firstname' => $request->firstname,
    //         'lastname' => $request->lastname,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'phonenumber' => $request->phonenumber,
    //         'address' => $request->address,
    //         'postal_code' => $request->postal_code,
    //         'status' => $request->status,
    //         'profile_picture_id' => $profilePictureId,
    //     ]);

    //     return response()->json(['message' => 'User created successfully']);
    // }

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
                return '
                <a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-primary">Edit</a>
                <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')">Delete</button>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function getUsersData()
    {
        $users = User::select(['id', 'firstname', 'lastname', 'email', 'phonenumber', 'status']);  // Specify fields to retrieve

        return datatables()->of($users)
            ->addColumn('action', function ($user) {
                return '
                    <a href="' . route('users.show', $user->id) . '" class="btn btn-info btn-sm">View</a>
                    <a href="' . route('users.edit', $user->id) . '" class="btn btn-primary btn-sm">Edit</a>
                    <button onclick="deleteUser(' . $user->id . ')" class="btn btn-danger btn-sm">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validation for all fields
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

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture_id) {
                $oldAttachment = Attachment::find($user->profile_picture_id);
                Storage::delete($oldAttachment->path); // Delete old file
                $oldAttachment->delete();
            }

            $path = $request->file('profile_picture')->store('profile_pictures');
            $attachment = Attachment::create(['path' => $path]);
            $user->profile_picture_id = $attachment->id;
        }

        // Update the user
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


    /**
     * Remove the specified resource from storage.
     */
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

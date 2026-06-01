<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|digits:10|unique:users,mobile',
                'password' => 'required|string|min:6|max:24',
            ]);

            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            if ($user) {
                return redirect()->route('users')->with('success', 'User added successfully!');
            }

            return redirect()->back()->with('error', 'Failed to add user. Please try again.');
        }

        return view('users.add');
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:6|max:24';
            }

            $data = $request->validate($rules);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            }

            $updated = $user->update($data);

            if ($updated) {
                return redirect()->route('users')->with('success', 'User details updated successfully!');
            }

            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }

        return view('users.edit', compact('user'));
    }

    public function delete($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        $user = User::findOrFail($id);
        
        if ($user->delete()) {
            return redirect()->route('users')->with('success', 'User deleted successfully!');
        }

        return redirect()->back()->with('error', 'Failed to delete user.');
    }
}

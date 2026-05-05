<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = User::where('email', $data['email'])->first();
            if(!empty($user)){
                $user = $user->toArray(); 
            }
            if (empty($user)) {
                return redirect()->back()->with('error', 'User Does not exist.');
            }
            if (!Hash::check($data['password'], $user['password'])) {
                return redirect()->back()->with('error', 'Invalid Password.');
            }
            if (Auth::attempt($data)) {
                return redirect()->route('dashboard')->with('success', 'User logged in successfully');
            }
        }
        return view('auth.login');
    }
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {

            $data = $request->validate([
                'name' => 'required|min:3|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|max:24',
                'mobile' => 'required|digits:10|unique:users',
            ]);

            $user = User::create($data);
            if ($user) {
                return redirect()->route('dashboard')->with('success', 'User registered successfully');
            }
            return redirect()->back()->with('error', 'User not registered');
        }

        return view('auth.register');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return view('auth.login');
    }

}

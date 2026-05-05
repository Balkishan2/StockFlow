<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

 
class UserController
{
    
   

    public function dashboard(Request $request){
        
        return view('dashboard.dashboard');
    }
    
}

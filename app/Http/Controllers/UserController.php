<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed');
    }
    else {
        return view('homepage');
    }
}
    //
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success','You are logged out');
    }
    public function register(Request $request){
        $incommingFields = $request->validate([
            "username"=>["required","min:3","max:50", Rule::unique('users','username')],
            "email"=>["required","email", Rule::unique('users','email')], //unique:table,column
            "password"=> ["required", "min:8", "max:50", "confirmed"],
        ]);
        @$incommingFields['password'] = bcrypt($incommingFields['password']);
        $user = User::create($incommingFields);
        auth()->login($user);
        
        return redirect('/')->with('success','Thank you for creating an account');
    }

    public function login(Request $request){
        $incommingFields = $request->validate([
            'loginusername' => ['required'],
            'loginpassword' => ['required'],
        ]);
        if (auth()->attempt(['username' => $incommingFields['loginusername'], 'password' => $incommingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success','you are logged in as '.$incommingFields['loginusername']);
            # code...
        } else {
            return redirect('/')->with('error','Invalid login credentials');
        }
        
    }
}

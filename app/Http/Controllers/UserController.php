<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

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

    // get the profile
    public function profile(User $user){
        return view('profile-posts', ['avatar'=> $user -> avatar,'user' => $user -> username, 'posts' => $user->posts()->latest()->get(),'postCount'=>$user->posts()->count()]);
    }

    // show the avatar form
    public function showAvatarForm(){
        return view('avatar-form');
    }

    // store the avatar
    public function storeAvatar(Request $request){
         $request->validate([
            'avatar' => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
        ]);
        $manager = new ImageManager(new Driver());
        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imageData = $manager->read($request->file('avatar'))->resize(120,120)->encodeByExtension('jpg', 75);
        Storage::put('public/avatars/' . $filename, $imageData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar !== 'fallback-avatar.jpg'){
            Storage::delete(str_replace('/storage/','public/',$oldAvatar));
        }
        return back()->with('success','Avatar uploaded successfully');

        
        
        // auth()->user()->update($incommingFields);
        // return redirect('/profile/'.auth()->user()->username)->with('success','Avatar uploaded successfully');
    }
}

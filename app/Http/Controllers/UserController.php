<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function showCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed',['posts'=>auth()->user()->feedPosts()->latest()->paginate(4)]);
    }
    else {
     
        $postCount = Cache::remember('postCount', 20, function () {
            return Post::count();
        });
        
            
        return view('homepage', ['postCount' => $postCount]);
    }
}
    //
    public function logout() {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action'=>'logged out']));
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

    public function loginApi(Request $request){
        $incomingFields = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
    
        if(auth()->attempt(['username' => $incomingFields['username'], 'password' => $incomingFields['password']])){
            $user = User::where('username', $incomingFields['username'])->first();
            $token = $user->createToken('ourAppToken')->plainTextToken;
            return $token;
        }
        return 'Sorry';
    }

    public function login(Request $request){
        $incommingFields = $request->validate([
            'loginusername' => ['required'],
            'loginpassword' => ['required'],
        ]);
        if (auth()->attempt(['username' => $incommingFields['loginusername'], 'password' => $incommingFields['loginpassword']])) {
            $request->session()->regenerate();
            event(new OurExampleEvent(['username' => auth()->user()->username, 'action'=>'log in']));
            return redirect('/')->with('success','you are logged in as '.$incommingFields['loginusername']);
            # code...
        } else {
            return redirect('/')->with('error','Invalid login credentials');
        }
        
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

    // get shared data
    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id','=',auth()->user()->id],['followenduser','=',$user->id]])->count();
        };


        View::share('sharedData',['currentlyFollowing'=>$currentlyFollowing ,'avatar'=> $user -> avatar,'user' => $user -> username, 'postCount'=>$user->posts()->count(), 'followerCount'=>$user->followers()->count(), 'followingCount'=>$user->followings()->count()]);
    }
    // get the profile
    public function profile(User $user){
        $this->getSharedData($user);
        
         return view('profile-posts', [ 'posts' => $user->posts()->latest()->get()]);
    }

    // show the followers
    public function profileFollowers(User $user){
        $this->getSharedData($user);
        
        return view('profile-followers', [ 'followers' => $user->followers()->latest()->get()]);
    }

    // show the following

    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following', [ 'followings' => $user->followings()->latest()->get()]);
    }

    public function profileRaw(User $user){
        
        return response()->json(['theHtml' => view('profile-posts-only',['posts'=> $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Profile" ]);
    }

    // show the followers
    public function profileFollowersRaw(User $user){
        
        return response()->json(['theHtml' => view('profile-followers-only',['followers'=>  $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers" ]);
    }

    // show the following

    public function profileFollowingRaw(User $user){
        return response()->json(['theHtml' => view('profile-followings-only',['followings'=> $user->followings()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followings" ]);
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //create follow
    
    public function createFollow(User $user){
        // rules You cannot follow yourselef
        if($user->id == auth()->user()->id){
            return back()->with('error', 'You cannot follow yourself.');
        }

        // rules You cannot follow the same user twice
         $existCheck = Follow::where([['user_id','=',auth()->user()->id],['followenduser','=',$user->id]])->count();
         if($existCheck > 0){
             return back()->with('error', 'You are already following this user.');
         }

        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followenduser = $user->id;
        $newFollow->save();
        return back()->with('success', 'You are now following '.$user->username);
    }

    //delete follow
    public function removeFollow(User $user){
        Follow::where([['user_id','=',auth()->user()->id],['followenduser','=',$user->id]])->delete();
        return back()->with('success', 'You are no longer following ');
    }
}

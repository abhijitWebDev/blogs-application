<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // showcreate post form
    public function showCreateForm()
    {
        return view('create-post');
    }

    // post method for creating a new post
    public function storeNewPost(Request $request) {
        // validate the request
        $incommingFields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $incommingFields['title'] = strip_tags($incommingFields['title']);
        $incommingFields['body'] = strip_tags($incommingFields['body']);
        $incommingFields['user_id'] = auth()->id();

        $newPost= Post::create($incommingFields);
        return redirect("/post/{$newPost->id}")->with('success', 'Post created successfully!');

    }

    // view single post
    public function viewSinglePost(Post $post){
        // passing markdown and return html
        $post['body'] = Str::markdown($post->body);
       
        return view('single-post', ['post' => $post]);
        
    }

    // delete post
    public function deletePost(Post $post){
        if(auth()->user()->cannot('delete', $post)){
            return redirect('/')->with('error', 'You are not authorized to delete this post');
        }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted successfully');
    }


}

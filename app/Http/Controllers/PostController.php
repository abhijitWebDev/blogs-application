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
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted successfully');
    }

    //showEditForm
    public function showEditForm(Post $post){
        return view('edit-post', ['post' => $post]);
    }

    // update post
    public function updatePost(Request $request, Post $post){
        $incommingFields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $incommingFields['title'] = strip_tags($incommingFields['title']);
        $incommingFields['body'] = strip_tags($incommingFields['body']);
        $post->update($incommingFields);
        return back()->with('success', 'Post updated successfully');
    }

    // search posts
    public function searchPosts($searchTerm){
        $posts=POST::search($searchTerm)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }


}

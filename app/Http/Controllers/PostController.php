<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

        Post::create($incommingFields);
        return 'Post created successfully!';

    }
}

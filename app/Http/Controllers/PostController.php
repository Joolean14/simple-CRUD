<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function deletePost(Post $post) {

        if (auth()->user()->id === $post['user_id']) {
            $post->delete();
        }       
        return redirect('/');
    }


    public function updatePost(Request $request, Post $post) {
        
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }
        
        $incomingFields = $request->validate([
            'title' =>'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return redirect('/');
    }


    public function showEditScreen(Post $post) {

        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }

        return view('edit-post', ['post' => $post]);

    }


    public function createPost(Request $request) { // use request object


        // validate
        $incomingFields = $request->validate([
            'title' =>'required',
            'body' => 'required'
        ]);

        // strip_tags from fields
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        // auth
        $incomingFields['user_id'] = auth()->id();

        // create
        Post::create($incomingFields);

        // redirect
        return redirect('/');
    }
}

// Eloquent
// Database
// Auth
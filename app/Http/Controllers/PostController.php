<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function deletePost(Post $post) { // refers to imported model

        if (auth()->user()->id === $post['user_id']) { // if authenticated user->id === post->id 
            $post->delete();
        }       
        return redirect('/');
    }


    public function updatePost(Request $request, Post $post) { // referst to request object and post model object
        
        // if logged user id is different from post user id redirect to '/'
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }
        
        // Validate incoming HTTP requests, particularly those that involve user input from forms.
        $incomingFields = $request->validate([
            'title' =>'required',
            'body' => 'required'
        ]);

        // Strip the string from HTML tags, but allow HTML tags to be used
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        // (Eloquent ORM). This line updates the attributes of the $post model instance with the values from $incomingFields. 
        //The update method is a convenient way to update a model's attributes without manually setting each one.
        // The usage of the update method on the $post instance is a clear indicator of Eloquent usage.
        // In Laravel's Eloquent ORM, the update method is provided to update the attributes of a model instance
        // and persist the changes to the database.
        $post->update($incomingFields);
        return redirect('/');
    }


    public function showEditScreen(Post $post) {
        //This code checks if the authenticated user's ID is not equal to the user ID associated with the post. 
        // If the condition is true, it means that the authenticated user does not have the proper authorization
        // to edit the post, and they are redirected to the home page (return redirect('/');).
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }
        // ['key' => 'value']: This is an optional second parameter,
        // and it is an associative array that allows you to pass data to the view.
        // The keys in the array are variable names that you can use in your view file,
         // and the corresponding values are the data you want to pass.
        return view('edit-post', ['post' => $post]);

    }


    public function createPost(Request $request) { // use request object


        // Validate incoming HTTP requests, particularly those that involve user input from forms.
        $incomingFields = $request->validate([
            'title' =>'required',
            'body' => 'required'
        ]);

        // Strip the string from HTML tags, but allow HTML tags to be used
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        // auth
        $incomingFields['user_id'] = auth()->id();

        // Laravel Eloquent. The create method is a convenient way to instantiate 
        //a new Eloquent model and persist it to the database in a single step.
        Post::create($incomingFields);

        // redirect
        return redirect('/');
    }
}


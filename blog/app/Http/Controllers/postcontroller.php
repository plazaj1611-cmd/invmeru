<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class postcontroller extends Controller
{
    public function index()
    {
        return (view('posts.index'));
    }
    public function formun()
    {
        return view('posts.formun');
    }
    public function show($post, $category)
    {
        return view('posts.show', [
            'post' => $post,
            'category' => $category
        ]);
    }

}

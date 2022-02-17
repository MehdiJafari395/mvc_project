<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;

class HomeController extends Controller
{
    public function index()
    {
        $post = Post::paginate(2);
        echo '<pre>';
        print_r($post);
    }

    public function create()
    {
        echo 'hello from create';
    }

    public function edit()
    {
        echo 'hello from edit';
    }
    public function ff()
    {
        echo 'hello from ff';
    }
}
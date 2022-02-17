<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use System\Database\DBBuilder\DBBuilder;

class HomeController extends Controller
{
    public function index()
    {
        $o = new DBBuilder();
        echo 'hello2!! welcome to my mvc framework from Home controller ';
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
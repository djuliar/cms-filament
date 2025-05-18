<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class HomeController extends Controller
{

    function page()
    {
        return dd(Article::where('id', request()->input('id'))->get());
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Flight;
use App\Models\Airline;

class AirlinesController extends Controller
{
    public function index()
    {
        //get all posts
        $posts = Airline::latest()->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts);
    }
}

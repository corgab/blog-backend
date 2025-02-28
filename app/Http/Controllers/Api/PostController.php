<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\PostResource;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $posts = Post::where('status', 'published')
            ->with(['user', 'tags'])
            ->orderBy('created_at', 'desc')
            ->take($perPage)
            ->get();
    
        return PostResource::collection($posts);
    } 

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('tags', 'user');

        return new PostResource($post);
    }

    public function getFeaturedPosts(Request $request) {

        $perPage = $request->input('per_page', 5);

        $featuredPosts = Post::where('status', 'published')
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->with('tags')
            ->take($perPage)
            ->get();

        return PostResource::collection($featuredPosts);
    }
}

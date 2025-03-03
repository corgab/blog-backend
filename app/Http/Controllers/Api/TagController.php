<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;

use App\Models\Tag;

class TagController extends Controller
{
    public function index(Request $request) {
        $perPage = $request->input('per_page', 5);

        $tags = Tag::with(['posts' => function($query) {
            $query->where('status', 'published'); // Filtra i post con publisher
        }])
        ->take($perPage)
        ->get();

        return TagResource::collection($tags);
    }

    public function show(Tag $tag)
    {
        $tag->load([
            'posts' => function ($query) {
                $query->where('status', 'published');
            },
            'posts.tags'
        ]);
        return new TagResource($tag);
    }

    public function showFeatures(Tag $tag, Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $tag->load([
            'posts' => function ($query) use ($perPage) {
                $query->where('status', 'published')
                ->where('featured', true)
                ->take($perPage);
            },
            'posts.tags'
        ]);
        return new TagResource($tag);
    }

    public function getTagsWithPostCount(Request $request)
    {
        $tags = Tag::whereHas('posts', function ($query) {
            $query->where('status', 'published');
        })
        ->withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])
        ->get();
    
        return response()->json($tags);
    }
}

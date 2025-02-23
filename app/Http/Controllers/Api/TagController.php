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
        }])->paginate($perPage);

        return TagResource::collection($tags);
    }

    public function show(Tag $tag)
    {
        $tag->load(['posts' => function ($query) {
            $query->where('status', 'published');
        }]);
        return response()->json($tag);
    }
}

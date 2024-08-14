<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // api da frontend
        $perPage = $request->input('per_page', 5);

        // Recuperare i post
        $posts = Post::paginate($perPage); // Impaginazione

        // Logiche aggiuntive per ricerca

        $tag = request()->input('tag');

        // Esegui la query per ottenere i post
        $postsQuery = Post::query();

        if ($tag) {
            $postsQuery->whereHas('tags', function($query) use ($tag) {
                $query->where('name', $tag);
            });
        }

        // Paginazione dei risultati
        $posts = $postsQuery->paginate($perPage);
        

        return response()->json($posts);

    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Carica le immagini con il post
        $post->load('images','tags','technologies');

        // Costruisci la risposta
        $response = [
            'id' => $post->id,
            'title' => $post->title,
            'reading_time' => $post->reading_time,
            'featured' => $post->featured ? 'True' : 'False',
            'tags' => $post->tags->pluck('name'),
            'technologies' => $post->technologies->pluck('name'),
            'created_at' => $post->created_at->translatedFormat('d F Y '),
            'images' => $post->images->map(function($image) {
                return [
                    url('storage/' . $image->path), // URL Immagine
                    'is_featured' => $image->is_featured ? 'True' : 'False', // Copertina
                    //Alt
                ];
            }),
        ];

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

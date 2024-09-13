<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    
        // Inizializza la query per i post
        $postsQuery = Post::with('user','images','tags')->orderBy('id','desc');

    
        // Logiche aggiuntive per ricerca
        $tag = $request->input('tag');
    
        if ($tag) {
            $postsQuery->whereHas('tags', function($query) use ($tag) {
                $query->where('name', $tag);
            });
        }
    
        // Esegui la query e applica la paginazione
        $posts = $postsQuery->paginate($perPage);
    
        // Trasforma i dati della collezione
        $posts->getCollection()->transform(function($post) {
            $post->user_name = $post->user->name;

            $post->created_date = $post->created_at->translatedFormat('d F Y');

            $post->images = $post->images->map(function($image) {

                $image->link = url('storage/' . $image->path);
                return $image;
            });

            unset($post->created_at);
            return $post;
        });
    
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

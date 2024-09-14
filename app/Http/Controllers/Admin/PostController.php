<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Image;

use Intervention\Image\Facades\Image as InterventionImage; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $user = Auth::user();
        // $posts = Post::all();
        $posts = Post::orderBy('created_at', 'desc')->where('user_id', $user->id)->with('user','tags')->get();

        // dd($posts);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Recupero l'utente 
        $user = Auth::user();
        // Trova i post associati all'utente
        $posts = Post::where('user_id', $user->id)->first();
        // Tutti i tags
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('posts.create', compact('user','posts','tags'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Ottieni i dati validati dalla richiesta
        $form_data = $request->validated();

        // Assegna l'ID dell'utente al campo 'user_id'
        $form_data['user_id'] = Auth::id();

        // Imposta lo stato su draft
        $form_data['status'] = 'draft';

        // Creazione slug univoco
        $base_slug = Str::slug($form_data['title']);
        $slug = $base_slug;
        $n = 0;
        do {
            $find = Post::where('slug', $slug)->first();
            if ($find !== null) {
                $n++;
                $slug = $base_slug . '-' . $n;
            }
        } while ($find !== null);
        $form_data['slug'] = $slug;

        // Creazione nuovo post
        $new_post = Post::create($form_data);

        // Gestione dell'immagine
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::uuid() . '.webp';
            
            // Converti l'immagine in formato webp
            $convertedImage = InterventionImage::make($image)
                ->encode('webp', 90);

            $path = $image->storeAs('/images', $fileName);

            Image::create([
                'post_id' => $new_post->id,
                'path' => $path,
                'is_featured' => true,
            ]);
        }   

        // Assegna i tag al post
        if (isset($form_data['tag_id'])) {
            $new_post->tags()->sync($form_data['tag_id']);
        }

        // Ritorna alla pagina degli indici dei post
        return to_route('posts.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

        $post->load('images', 'tags');

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        // Carica le relazioni tags
        $post->load('tags');
        
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('posts.edit', compact('post','tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Ottieni i dati validati dalla richiesta
        $form_data = $request->validated();
    
        // Aggiorna il titolo e crea un nuovo slug se il titolo è cambiato
        if ($post->title !== $form_data['title']) {
            $base_slug = Str::slug($form_data['title']);
            $slug = $base_slug;
            $n = 0;
            // Assicurati che il nuovo slug sia unico
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $n++;
                $slug = $base_slug . '-' . $n;
            }
            $form_data['slug'] = $slug;
        }
        
        // Aggiorna il post con i dati validati
        $post->update($form_data);
    
        // Sincronizza i tag
        $post->tags()->sync($request->input('tag_id', []));

        $redirectFrom = $request->input('redirect_from');
        
        if ($redirectFrom && str_contains($redirectFrom, '/drafts')) {
            // Se l'URL di origine contiene /drafts, reindirizza a drafts
            return redirect()->route('posts.drafts');
        }
      
        // Redireziona alla lista dei post
        return redirect()->route('posts.index');

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('posts.index');
    }

    public function drafts()
    {
        $user = Auth::user();
        if (!$user->hasRole('editor') && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        // Recupera i draft
        $drafts = Post::where('status', 'draft')->get();
        return view('posts.drafts', compact('drafts'));
    }

}

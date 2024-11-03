<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Image;
use App\Models\PostSection;


use Intervention\Image\Facades\Image as InterventionImage; 
use Illuminate\Support\Facades\Storage;
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
        if($user->hasRole('admin') || $user->hasRole('editor')) {
            $posts = Post::orderBy('created_at', 'desc')
            ->with('user','tags')
            ->get();

        } else {
            $posts = Post::orderBy('created_at', 'desc')->where('user_id', $user->id)->with('user','tags')->get();

        }

        // dd($posts);

        return view('posts.index', compact('user','posts'));
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

        $form_data['title'] = strtoupper($form_data['title']);
        $form_data['user_id'] = Auth::id(); // Assegna l'ID dell'utente autenticato
        $form_data['status'] = $request->input('status', 'draft'); // Imposta lo stato, predefinito a 'draft'
    
        // Creazione slug univoco
        $base_slug = Str::slug($form_data['title']);
        $slug = $base_slug;
        $n = 0;
    
        do {
            $find = Post::where('slug', $slug)->withTrashed()->first();
            if ($find !== null) {
                $n++;
                $slug = $base_slug . '-' . $n; // Incrementa lo slug se già esistente
            }
        } while ($find !== null);
    
        $form_data['slug'] = $slug;
    
        // Creazione del nuovo post
        $new_post = Post::create($form_data);
    
        // Gestione delle sezioni
        if ($request->has('sections')) {
            foreach ($request->input('sections') as $index => $sectionData) {

                $sectionData['title'] = strtoupper($sectionData['title']);
                $sectionData['content'] = ucwords($sectionData['content']);

                // Crea la sezione e recupera l'ID
                $section = PostSection::create([
                    'post_id' => $new_post->id,
                    'title' => $sectionData['title'] ?? null,
                    'content' => $sectionData['content'],
                    'order' => $index + 1,
                ]);
                

                if ($request->hasFile('sections.' . $index . '.image')) {
                    $image = $request->file('sections.' . $index . '.image'); 
                    if ($image->isValid()) {
                        // Procedi con la conversione e il salvataggio
                        $fileName = Str::uuid() . '.webp';
                        $convertedImage = InterventionImage::make($image)->encode('webp', 90);
                        $path = $image->storeAs('/images', $fileName);
                        // Crea l'immagine della sezione
                        Image::create([
                            'post_id' => $new_post->id,
                            'section_id' => $section->id,
                            'path' => $path,
                            'is_featured' => false, // Section
                        ]);
                    }
                }
        }

        // Gestione dell'immagine di copertura
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::uuid() . '.webp';
            $convertedImage = InterventionImage::make($image)
                ->encode('webp', 90);
            $path = $image->storeAs('/images', $fileName);
            
            // Crea l'immagine di copertura
            Image::create([
                'post_id' => $new_post->id,
                'path' => $path,
                'is_featured' => true, // Copertina
            ]);
        }
    
        // Assegna i tag al post
        if (isset($form_data['tag_id'])) {
            $new_post->tags()->sync($form_data['tag_id']);
        }

        // dd($form_data);
    
        // Reindirizza all'indice dei post con un messaggio di successo
        return to_route('posts.index')->with('success', 'Post creato con successo!');
        }
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $user = Auth::user();
        
        $sections = PostSection::with('images')
            ->where('post_id', $post->id)
            ->orderBy('order')
            ->get();
    
        return view('posts.show', compact('post', 'user', 'sections'));
    }
    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        $user = Auth::user();
        // Carica le relazioni tags
        $post->load('tags','sections.images','images');

        $tags = Tag::orderBy('name', 'asc')->get();
        
        // dd($post->status);

        //  se il post è pubblico e l'utente è author e editor
        if($post->status == 'published' && $user->hasRole(['author', 'editor'])) {
            return redirect()->route('posts.index')->with('errMessage', 'Non puoi modificare un post pubblico');
        }

        return view('posts.edit', compact('user','post','tags'));
        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
{
    // Ottieni i dati validati dalla richiesta
    $form_data = $request->validated();
    
    // Controllo del ruolo utente
    if (Auth::user()->hasRole('author')) {
        $form_data['status'] = 'draft'; // Forza lo stato a "draft"
    } elseif (!in_array($form_data['status'], ['draft', 'published'])) {
        return redirect()->back()->withErrors(['status' => 'Stato non valido']);
    }

    // Aggiornamento del titolo e generazione dello slug
    if ($post->title !== $form_data['title']) {
        $base_slug = Str::slug($form_data['title']);
        $slug = $base_slug;
        $n = 0;

        while (Post::where('slug', $slug)->where('id', '!=', $post->id)->withTrashed()->exists()) {
            $n++;
            $slug = $base_slug . '-' . $n;
        }
        $form_data['slug'] = $slug;
    }

    $form_data['title'] = strtoupper($form_data['title']);


    // Aggiorna il post
    $post->update($form_data);
    $post->tags()->sync($request->input('tag_id', [])); // Sincronizza i tag

    // Gestione delle sezioni
    if ($request->has('sections')) {
        $existingSectionIds = [];
        $nextOrder = $post->sections()->max('order') + 1; // Trova il prossimo ordine disponibile

        foreach ($request->input('sections') as $index => $section) {
            // Verifica se la sezione ha un ID per determinare se è esistente o nuova
            if (isset($section['id'])) {

                $section['title'] = strtoupper($section['title']);
                $section['content'] = ucfirst($section['content']);
                // Trova la sezione esistente
                $postSection = $post->sections()->find($section['id']);
                if ($postSection) {
                    // Aggiorna la sezione esistente
                    $postSection->fill([
                        'title' => $section['title'] ?? null,
                        'content' => $section['content'],
                        'order' => $postSection->order, // Mantieni l'ordine esistente
                    ]);
                }
            } else {
                // Crea una nuova sezione
                $postSection = new PostSection();
                $postSection->fill([
                    'post_id' => $post->id,
                    'title' => $section['title'] ?? null,
                    'content' => $section['content'],
                    'order' => $nextOrder++, // Assegna il nuovo ordine
                ]);
            }

            // Gestione dell'immagine se fornita
            if ($request->hasFile("sections.$index.image")) {
                $image = $request->file("sections.$index.image");
                $fileName = Str::uuid() . '.webp';

                // Converti l'immagine
                $convertedImage = InterventionImage::make($image)->encode('webp', 90);
                $path = $image->storeAs('/images', $fileName);

                // Salva l'immagine per la sezione
                $postSection->images()->updateOrCreate(
                    ['post_id' => $post->id, 'is_featured' => false], 
                    ['path' => $path]
                );
            }

            // Salva la sezione
            $postSection->save();
            $existingSectionIds[] = $postSection->id; // Aggiungi l'ID della sezione salvata
        }

        // Rimuovi le sezioni non più presenti
        $post->sections()->whereNotIn('id', $existingSectionIds)->delete();
    }

    // Gestione dell'immagine principale se aggiornata
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = Str::uuid() . '.webp';
        $convertedImage = InterventionImage::make($image)->encode('webp', 90);
        $path = $image->storeAs('/images', $fileName);

        // Aggiorna l'immagine in evidenza
        $post->images()->updateOrCreate(
            ['is_featured' => true], 
            ['path' => $path, 'is_featured' => true]
        );
    }

    // Redirect
    $redirectFrom = $request->input('redirect_from');
    if ($redirectFrom && str_contains($redirectFrom, '/drafts')) {
        return redirect()->route('posts.drafts');
    }

    return redirect()->route('posts.index');
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        $user = Auth::user();

        if($post->status == 'published' && $user->hasRole('author')) {
            return back()->with('errMessage', 'Non puoi eliminare questo post perchè è pubblico');
        }

        $post->delete();

        return to_route('posts.index');
    }

    public function drafts()
    {
        $user = Auth::user();
    
        $drafts = Post::where('status', 'draft')->get();
        
        return view('posts.drafts',compact('drafts', 'user'));
    }

    public function publish(Post $post)
    {
        $post->status = 'published';

        $post->save();

        return redirect()->route('posts.drafts')->with('success', 'Post pubblicato con successo');

    }

    public function trash()
    {
        $user = Auth::user();

        if($user->hasRole('admin')) {

            $user = Auth::user();
            $posts = Post::onlyTrashed()
            ->orderBy('created_at','desc')
            // ->where('user_id', $user->id)
            ->get();

            return view('posts.trash', compact('posts','user'));
        } else {

            $user = Auth::user();
            $posts = Post::onlyTrashed()
            ->orderBy('created_at','desc')
            ->where('user_id', $user->id)
            ->get();

            return view('posts.trash', compact('posts','user'));
        } 


    }

    public function restore($slug)
    {
        // Trova il post soft deleted usando lo slug
        $post = Post::withTrashed()->where('slug', $slug)->first();
    
        if ($post) {
            $post->restore(); // Ripristina il post
            return redirect()->route('posts.trash')->with('success', 'Post ripristinato correttamente!');
        }
    
        abort(404);
    }
}

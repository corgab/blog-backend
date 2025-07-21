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
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('editor')) {
            $posts = Post::orderBy('created_at', 'desc')
                ->with('user', 'tags')
                ->get();
        } else {
            $posts = Post::orderBy('created_at', 'desc')->where('user_id', $user->id)->with('user', 'tags')->get();
        }

        // dd($posts);

        return view('posts.index', compact('user', 'posts'));
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

        return view('posts.create', compact('user', 'posts', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Ottieni i dati validati dalla richiesta
        $form_data = $request->validated();

        // Gestisci l'immagine
        if ($request->hasFile('image')) {
            // Prendi il file
            $file = $request->file('image');
            // Nome
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            // Salva in
            $file->storeAs('/cover_images', $fileName);

            $form_data['image'] = url('storage/cover_images/' . $fileName);
        }

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

        $new_post->tags()->sync($request->input('tag_id'));

        return to_route('posts.index')->with('success', 'Post creato con successo!');
    }



    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $user = Auth::user();

        return view('posts.show', compact('post', 'user'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $user = Auth::user();
        // Carica le relazioni tags
        $post->load('tags');

        $tags = Tag::orderBy('name', 'asc')->get();

        // Aggiungi la logica per trasformare i percorsi delle immagini in URL completi
        // $post->description = $this->updateImageUrls($post->description);

        // se il post è pubblico e l'utente è author e editor
        if ($post->status == 'published' && $user->hasRole(['author', 'editor'])) {
            return redirect()->route('posts.index')->with('errMessage', 'Non puoi modificare un post pubblico');
        }

        return view('posts.edit', compact('user', 'post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Ottieni i dati validati dalla richiesta
        $form_data = $request->validated();

        // Gestisci l'immagine
        if ($request->hasFile('image')) {
            // Se ha gia un immagine
            if ($post->image) {

                $urlPath = parse_url($post->image, PHP_URL_PATH);

                // Rimuove '/storage'
                $imagePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                // dd($imagePath);

                // Verifica se il file esiste nel disco 'public'
                if (Storage::disk('public')->exists($imagePath)) {
                    // Elimina il file
                    Storage::disk('public')->delete($imagePath);
                } else {
                    Log::warning("Immagine copertina del Post non eliminata: " . $imagePath);
                }
            }

            // Prendi il file
            $file = $request->file('image');

            // Nome
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            // Salva in
            $file->storeAs('/cover_images', $fileName);

            $form_data['image'] = url('storage/cover_images/' . $fileName);
        }

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

        // Aggiorna il post
        $post->update($form_data);
        $post->tags()->sync($request->input('tag_id', [])); // Sincronizza i tag

        return redirect()->route('posts.index')->with('success', 'Post modificato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        $user = Auth::user();

        if ($post->status == 'published' && $user->hasRole('author')) {
            return back()->with('errMessage', 'Non puoi eliminare questo post perchè è pubblico');
        }

        $post->delete();

        return to_route('posts.index');
    }

    public function drafts()
    {
        $user = Auth::user();

        $drafts = Post::where('status', 'draft')->get();

        return view('posts.drafts', compact('drafts', 'user'));
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

        if ($user->hasRole('admin')) {

            $posts = Post::onlyTrashed()
                ->orderBy('created_at', 'desc')
                // ->where('user_id', $user->id)
                ->get();

            return view('posts.trash', compact('posts', 'user'));
        } else {

            $user = Auth::user();
            $posts = Post::onlyTrashed()
                ->orderBy('created_at', 'desc')
                ->where('user_id', $user->id)
                ->get();

            return view('posts.trash', compact('posts', 'user'));
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

    public function permDelete($slug)
    {
        $post = Post::withTrashed()->where('slug', $slug)->first();

        if ($post) {
            $post->forceDelete(); // Elimina perma
            return back()->with('success', 'Post permanentemente eliminato!');
        }
    }

    public function uploadImage(Request $request)
    {

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            try {
                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                // Salva il file nella cartella 'uploads' dentro storage/app/public
                $file->storeAs('/post_images', $fileName);
                // Log::alert(url('storage/uploads/' . $fileName));
                // Restituisci l'URL completo dell'immagine
                return response()->json([
                    'success' => true,
                    'imageUrl' => url('storage/post_images/' . $fileName) // Assicurati che l'URL restituito sia completo
                ]);
            } catch (\Exception $e) {
                // In caso di errore
                return response()->json(['success' => false, 'message' => 'Errore nel caricamento dell\'immagine: ' . $e->getMessage()]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Nessun file immagine valido trovato.']);
    }

    public function approveIndex()
    {

        $posts = Post::where('status', 'draft')->get();

        return view('posts.approve', compact('posts'));
    }

    public function approve(Post $post) {

        $post->status = 'approved';

        $post->save();

        return redirect()->route('posts.approve', $post)->with('success', 'Post approvato con successo');
    }


}

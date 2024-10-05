<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        // Solo utenti con permesso
         $this->middleware('permission:manage tags');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'asc')->get();
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $form_data = $request->validated();
    
        // Creazione slug univoco
        $base_slug = Str::slug($form_data['name']);
        $slug = $base_slug;
        $n = 0;
    
        do {
            $find = Tag::where('slug', $slug)->first();
            if ($find !== null) {
                $n++;
                $slug = $base_slug . '-' . $n; // Incrementa lo slug se già esistente
            }
        } while ($find !== null);
    
        $form_data['slug'] = $slug;
    
        Tag::create($form_data);
    
        return to_route('tags.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return to_route('tags.index');
    }
}

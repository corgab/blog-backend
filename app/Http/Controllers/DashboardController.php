<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function index()
    
    {
        $user = Auth::user();
        $totalPosts = Post::count();
        $totalTags = Tag::count();
        $totalDrafts = Post::count();
        // Se l'utente è admin o editor
        if($user->hasRole('admin') || $user->hasRole('editor')) {
            $posts = Post::orderBy('created_at', 'desc')
            ->with('user','tags')
            ->take(5)
            ->get();
            
        } else {
            $posts = Post::orderBy('created_at', 'desc')
            ->where('user_id', $user->id)
            ->with('user','tags')
            ->take(5)
            ->get();
        }


        // dd($posts);

        return view('dashboard', compact('posts','user','totalPosts','totalTags','totalDrafts'));
    }

}

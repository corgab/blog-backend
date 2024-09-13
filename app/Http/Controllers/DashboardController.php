<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    
    {
        $user = Auth::user();
        // $posts = Post::all();
        $posts = Post::orderBy('created_at', 'desc')
        ->where('user_id', $user->id)
        ->with('user','tags')
        ->take(5)
        ->get();

        // dd($posts);

        return view('dashboard', compact('posts','user'));
    }

}

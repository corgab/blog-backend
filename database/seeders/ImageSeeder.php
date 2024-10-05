<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostSection;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Immagine copertina
        $posts = Post::all();
        foreach ($posts as $post) {
            Image::create([
                'post_id' => $post->id,
                'path' => 'images/test-post.webp', 
                'is_featured' => true,
                'alt' => 'Copertura per ' . $post->title
            ]);
        }

        // Immagini per le sezioni dei post
        $sections = PostSection::all();
        foreach ($sections as $section) {
            Image::create([
                'post_id' => $section->post_id,
                'section_id' => $section->id,
                'path' => 'images/test-post.webp',
                'is_featured' => false,
                'alt' => 'Immagine per la sezione ' . $section->title
            ]);
        }
    }
}

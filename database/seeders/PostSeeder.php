<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Technology;


class PostSeeder extends Seeder
{

    // Funzione recupero dati da CSV
    public function getCSVData(string $path) {
        $data = [];

        $file_stream = fopen($path, 'r');

        if ($file_stream === false) {
            exit('fail' . $path);
        }

        while (($row = fgetcsv($file_stream)) !== false) {
            $data[] = $row;
        }

        fclose($file_stream);

        return $data;
    }

    public function run(): void
    {
        
        $data = $this->getCSVData(__DIR__.'/csv/posts.csv');


        foreach ($data as $index => $row) {
            if ($index !== 0) {
                $new_post = new Post();
                $new_post->title = $row[0];
                $new_post->content = $row[1];
                $new_post->slug = Str::slug($new_post->title, '-');
                $new_post->featured = filter_var($row[2], FILTER_VALIDATE_BOOLEAN);
                $new_post->status = $row[3];
                $new_post->user_id = $row[4];
    
                $new_post->save();
    
                // Recupera gli ID di tutti i tag in un array
                $tags_id = Tag::all()->pluck('id')->all();
    
                if (!empty($tags_id)) {
                    $random_tag_id = $tags_id[array_rand($tags_id)];
                    $new_post->tags()->attach($random_tag_id);
                }
            }
        }
        

    }

}

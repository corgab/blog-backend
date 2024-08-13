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

    public function run(Faker $faker): void
    {
        
        $data = $this->getCSVData(__DIR__.'/csv/posts.csv');

        foreach($data as $index=>$row) {
            if($index !== 0) {

                $user_id = $faker->numberBetween(1, 11);;

                $new_post = new Post();

                $new_post->user_id = $user_id;
                $new_post->title = $row[0];
                $new_post->slug = Str::slug($new_post->title, '-');
                $new_post->content = $row[1];
                $new_post->created_at = $row[2];
                $new_post->difficulty = $row[4];
                $new_post->featured = $row[5];

                $new_post->save();

                // Recupera gli ID di tutti i tags in un array
                $tags_id = Tag::all()->pluck('id')->all();
                $technologies_id = Technology::all()->pluck('id')->all();

                $random_tag = $faker->randomElements($tags_id, 2);
                $random_tech = $faker->randomElements($technologies_id, 2);

                $new_post->tags()->attach($random_tag);
                $new_post->technologies()->attach($random_tech);

            }
        }

    }
}

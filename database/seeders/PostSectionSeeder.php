<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostSection;
use App\Models\Post;

class PostSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getCSVData(__DIR__ . '/csv/posts_sections.csv');

        foreach ($data as $index => $row) {
            if ($index !== 0) {
                $post_id = $row[0];
                $new_section = new PostSection();

                $new_section->post_id = $post_id;
                $new_section->title = $row[1];
                $new_section->content = $row[2];
                $new_section->order = $row[3];

                $new_section->save();
            }
        }
    }

     // Funzione recupero dati da CSV
     public function getCSVData(string $path)
     {
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
}

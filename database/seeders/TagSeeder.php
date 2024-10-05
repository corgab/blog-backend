<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getCSVData(__DIR__.'/csv/tags.csv');
        foreach ($data as $index => $row) {
            if($index != 0) {

                    $new_tag = new Tag();

                    $new_tag->name = $row[0];
                    $new_tag->slug = Str::slug($new_tag->name, '-');

                    $new_tag->save();
            }
        }

    }


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
}

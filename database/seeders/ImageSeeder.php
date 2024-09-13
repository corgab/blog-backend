<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagePath = 'images/927d6381-885e-4e1c-8c1f-fc575907cfa9.webp';

        for($i = 1; $i <= 100; $i++) {
            $newImage = New Image();

            $newImage->path = $imagePath;
            $newImage->post_id = $i;
            $newImage->is_featured = 1;

            $newImage->save();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['Code', 'Guides'];

        foreach($tags as $tag) {
            $new_tag = New Tag();

            $new_tag->name = $tag;

            $new_tag->save();
        };

    }
}

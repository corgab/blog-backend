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
        $tags = [
            'Tech',
            'App',
            'Social',
            'Gadget',
            'Videogiochi',
            'Sicurezza',
            'Casa',
            'Auto',
            'Notizie',
            'Curiosità',
            'Fotografia',
            'Computer',
            'Salute'
        ];

        foreach($tags as $tag) {
            $new_tag = New Tag();

            $new_tag->name = $tag;
            

            $new_tag->save();
        };

    }
}

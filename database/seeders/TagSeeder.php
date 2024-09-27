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
        $tags = [
            'Alimentazione Consapevole',
            'Mindfulness e Meditazione',
            'Fitness e Movimento',
            'Salute Mentale',
            'Rimedi Naturali e Fitoterapia',
            'Spiritualità e Crescita Personale',
            'Sostenibilità e Stili di Vita',
            'Comunità e Supporto',
            'Eventi e Attività Olistiche',
            'Lifestyle e Produttività',
        ];

        foreach($tags as $tag) {
            $new_tag = New Tag();

            $new_tag->name = $tag;
            $new_tag->slug = Str::slug($new_tag->name, '-');            
            $new_tag->save();
        };

    }
}

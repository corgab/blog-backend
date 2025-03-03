<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Newsletter;
use Faker\Factory as Faker;

class NewsletterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Newsletter::create([
                'email' => $faker->unique()->safeEmail,
            ]);
        }

    }
}



<?php

// database/seeders/TagSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Fantasy',
            'Sci-Fi',
            'Anime',
            'Manga',
            'Digital Art',
            'Concept Art',
            'Character Design',
            'Environment',
            'Portrait',
            'Abstract',
            'Colorful',
            'Monocromo',
            'Minimalista',
            'Realista',
            'Cartoon',
            'Cyberpunk',
            'Steampunk',
            'Medieval',
            'Futurista',
            'Naturaleza',
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
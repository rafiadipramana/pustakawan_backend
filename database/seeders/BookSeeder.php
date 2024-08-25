<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Faker\Factory;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed 10 books to the database using Faker
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            Book::create([
                'title' => $faker->words(3, true),
                'author' => $faker->name,
                'description' => $faker->paragraph(3),
                'price' => $faker->randomFloat(2, 1000, 10000),
                'image_url' => $faker->imageUrl(640, 480, 'books', true),
                'published_at' => $faker->date(),
            ]);
        }
    }
}

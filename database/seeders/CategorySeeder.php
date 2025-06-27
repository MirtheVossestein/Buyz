<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Auto', 'Fiets', 'Boeken', 'Elektronica', 'Kleding', 'Meubels', 'Huisdieren', 'Sport', 'Spellen', 'Overig'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}

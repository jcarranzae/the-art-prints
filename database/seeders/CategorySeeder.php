<?php

// ============================================
// SEEDERS - Crear en database/seeders/
// ============================================

// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ilustraciones Digitales',
                'slug' => 'ilustraciones-digitales',
                'description' => 'Ilustraciones originales en formato digital de alta calidad',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Arte Conceptual',
                'slug' => 'arte-conceptual',
                'description' => 'Diseños y conceptos artísticos únicos',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Personajes',
                'slug' => 'personajes',
                'description' => 'Ilustraciones de personajes originales y fan art',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Paisajes',
                'slug' => 'paisajes',
                'description' => 'Paisajes fantásticos y escenarios imaginarios',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Abstracto',
                'slug' => 'abstracto',
                'description' => 'Arte abstracto y experimental',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Productos Físicos',
                'slug' => 'productos-fisicos',
                'description' => 'Merchandising y productos impresos',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Crear subcategorías para "Productos Físicos"
        $physicalCategory = Category::where('slug', 'productos-fisicos')->first();
        
        $subcategories = [
            [
                'name' => 'Camisetas',
                'slug' => 'camisetas',
                'description' => 'Camisetas con ilustraciones estampadas',
                'parent_id' => $physicalCategory->id,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Tazas',
                'slug' => 'tazas',
                'description' => 'Tazas personalizadas con arte original',
                'parent_id' => $physicalCategory->id,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Pósters',
                'slug' => 'posters',
                'description' => 'Pósters de alta calidad',
                'parent_id' => $physicalCategory->id,
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($subcategories as $subcategory) {
            Category::create($subcategory);
        }
    }
}
<?php

// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Tag;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::whereNull('parent_id')->get();
        $tags = Tag::all();

        // Ilustraciones Digitales
        $products = [
            [
                'name' => 'Dragón Místico',
                'description' => 'Ilustración digital de un dragón en su guarida',
                'long_description' => 'Ilustración digital de alta resolución que representa un majestuoso dragón rodeado de tesoros en una caverna mística. Perfecta para impresión o uso digital. Archivo en alta resolución 4000x6000px.',
                'price' => 29.99,
                'compare_price' => 39.99,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'ilustraciones-digitales')->first()->id,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 3,
                'tags' => ['Fantasy', 'Digital Art', 'Colorful'],
            ],
            [
                'name' => 'Guerrera Espacial',
                'description' => 'Arte conceptual de personaje sci-fi',
                'long_description' => 'Diseño de personaje original de una guerrera del espacio. Ideal para proyectos de videojuegos o ilustración editorial. Incluye versión con y sin fondo.',
                'price' => 34.99,
                'compare_price' => null,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'arte-conceptual')->first()->id,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 3,
                'tags' => ['Sci-Fi', 'Character Design', 'Futurista'],
            ],
            [
                'name' => 'Bosque Encantado',
                'description' => 'Paisaje fantástico con criaturas mágicas',
                'long_description' => 'Ilustración de ambiente de un bosque místico lleno de criaturas fantásticas y vegetación bioluminiscente. Perfecta para fondos de escritorio o impresión decorativa.',
                'price' => 24.99,
                'compare_price' => 34.99,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'paisajes')->first()->id,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 5,
                'tags' => ['Fantasy', 'Environment', 'Naturaleza'],
            ],
            [
                'name' => 'Samurái Cibernético',
                'description' => 'Fusión de tradición y tecnología',
                'long_description' => 'Ilustración que combina elementos tradicionales japoneses con estética cyberpunk. Un samurái equipado con tecnología avanzada en las calles de Neo-Tokyo.',
                'price' => 39.99,
                'compare_price' => null,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'personajes')->first()->id,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 3,
                'tags' => ['Cyberpunk', 'Character Design', 'Futurista'],
            ],
            [
                'name' => 'Retrato Abstracto',
                'description' => 'Interpretación abstracta de la figura humana',
                'long_description' => 'Obra de arte abstracto que explora la forma humana a través de colores vibrantes y formas geométricas. Ideal para decoración moderna.',
                'price' => 19.99,
                'compare_price' => 29.99,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'abstracto')->first()->id,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => null,
                'tags' => ['Abstract', 'Portrait', 'Colorful'],
            ],
            [
                'name' => 'Ciudad Steampunk',
                'description' => 'Metrópolis de la era victoriana alternativa',
                'long_description' => 'Ilustración detallada de una ciudad steampunk con dirigibles, engranajes y arquitectura victoriana. Gran detalle para explorar.',
                'price' => 44.99,
                'compare_price' => null,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'paisajes')->first()->id,
                'is_active' => true,
                'is_featured' => true,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 3,
                'tags' => ['Steampunk', 'Environment', 'Concept Art'],
            ],
            [
                'name' => 'Mago Minimalista',
                'description' => 'Diseño simple pero efectivo',
                'long_description' => 'Ilustración minimalista de un mago usando formas simples y colores planos. Perfecto para logos, iconos o diseño editorial moderno.',
                'price' => 14.99,
                'compare_price' => null,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'personajes')->first()->id,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 5,
                'tags' => ['Fantasy', 'Minimalista', 'Character Design'],
            ],
            [
                'name' => 'Criatura Marina',
                'description' => 'Bestia del océano profundo',
                'long_description' => 'Diseño de criatura original inspirada en la vida marina del abismo. Bioluminiscencia y formas alienígenas se combinan en este concept art.',
                'price' => 32.99,
                'compare_price' => 42.99,
                'type' => 'digital',
                'category_id' => $categories->where('slug', 'arte-conceptual')->first()->id,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 0,
                'track_inventory' => false,
                'download_limit' => 3,
                'tags' => ['Fantasy', 'Concept Art', 'Naturaleza'],
            ],
        ];

        foreach ($products as $productData) {
            $tagNames = $productData['tags'];
            unset($productData['tags']);

            $product = Product::create($productData);

            // Asignar tags
            $productTags = Tag::whereIn('name', $tagNames)->get();
            $product->tags()->attach($productTags);

            // Crear imagen de ejemplo
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/' . $product->slug . '.jpg',
                'thumbnail_path' => 'products/thumbnails/' . $product->slug . '.jpg',
                'is_primary' => true,
                'order' => 1,
            ]);
        }

        // Crear algunos productos físicos de ejemplo
        $physicalCategory = Category::where('slug', 'camisetas')->first();
        
        if ($physicalCategory) {
            $tshirt = Product::create([
                'name' => 'Camiseta Dragón Místico',
                'description' => 'Camiseta 100% algodón con el diseño del Dragón Místico',
                'long_description' => 'Camiseta de alta calidad con impresión DTG del diseño "Dragón Místico". Disponible en múltiples tallas y colores. Material suave y duradero.',
                'price' => 24.99,
                'type' => 'physical',
                'category_id' => $physicalCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'stock' => 50,
                'track_inventory' => true,
                'sku' => 'TSH-DRAG-001',
                'weight' => 0.2,
            ]);

            // Crear variantes de talla/color
            $sizes = ['S', 'M', 'L', 'XL'];
            $colors = ['Negro', 'Blanco', 'Gris'];

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    \App\Models\ProductVariant::create([
                        'product_id' => $tshirt->id,
                        'name' => "Talla {$size} - {$color}",
                        'sku' => "TSH-DRAG-{$size}-" . substr($color, 0, 1),
                        'stock' => rand(5, 15),
                        'attributes' => [
                            'size' => $size,
                            'color' => $color,
                        ],
                        'is_active' => true,
                    ]);
                }
            }

            ProductImage::create([
                'product_id' => $tshirt->id,
                'image_path' => 'products/tshirt-dragon.jpg',
                'thumbnail_path' => 'products/thumbnails/tshirt-dragon.jpg',
                'is_primary' => true,
                'order' => 1,
            ]);

            $tshirt->tags()->attach(Tag::whereIn('name', ['Fantasy', 'Digital Art'])->get());
        }
    }
}
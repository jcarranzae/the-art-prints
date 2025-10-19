<?php

// app/Livewire/Admin/Products/ProductCreate.php
namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductImage;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Crear Producto - Admin')]
class ProductCreate extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('nullable|string')]
    public $long_description = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('nullable|numeric|min:0')]
    public $compare_price = '';

    #[Validate('required|in:digital,physical')]
    public $type = 'digital';

    #[Validate('nullable|exists:categories,id')]
    public $category_id = '';

    #[Validate('nullable|string|max:50')]
    public $sku = '';

    #[Validate('required|boolean')]
    public $is_active = true;

    #[Validate('required|boolean')]
    public $is_featured = false;

    #[Validate('nullable|integer|min:0')]
    public $stock = 0;

    #[Validate('required|boolean')]
    public $track_inventory = false;

    #[Validate('nullable|integer|min:1')]
    public $download_limit = 3;

    #[Validate('nullable|array')]
    public $images = [];

    public $selectedTags = [];

    public function save()
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'long_description' => $this->long_description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'type' => $this->type,
            'category_id' => $this->category_id ?: null,
            'sku' => $this->sku,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'stock' => $this->stock,
            'track_inventory' => $this->track_inventory,
            'download_limit' => $this->download_limit,
        ]);

        // Guardar imágenes
        if ($this->images) {
            foreach ($this->images as $index => $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        // Sincronizar tags
        if ($this->selectedTags) {
            $product->tags()->sync($this->selectedTags);
        }

        session()->flash('message', 'Producto creado correctamente');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();

        return view('livewire.admin.products.product-create', compact('categories', 'tags'));
    }
}
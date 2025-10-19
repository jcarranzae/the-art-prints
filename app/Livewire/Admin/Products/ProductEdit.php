<?php

// app/Livewire/Admin/Products/ProductEdit.php
namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductImage;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
class ProductEdit extends Component
{
    use WithFileUploads;

    public Product $product;

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

    public $is_active = true;
    public $is_featured = false;
    public $stock = 0;
    public $track_inventory = false;
    public $download_limit = 3;
    public $newImages = [];
    public $selectedTags = [];

    public function mount($id)
    {
        $this->product = Product::with(['tags', 'images'])->findOrFail($id);
        
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->long_description = $this->product->long_description;
        $this->price = $this->product->price;
        $this->compare_price = $this->product->compare_price;
        $this->type = $this->product->type;
        $this->category_id = $this->product->category_id;
        $this->sku = $this->product->sku;
        $this->is_active = $this->product->is_active;
        $this->is_featured = $this->product->is_featured;
        $this->stock = $this->product->stock;
        $this->track_inventory = $this->product->track_inventory;
        $this->download_limit = $this->product->download_limit;
        $this->selectedTags = $this->product->tags->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate();

        $this->product->update([
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

        // Guardar nuevas imágenes
        if ($this->newImages) {
            $lastOrder = $this->product->images()->max('order') ?? -1;
            
            foreach ($this->newImages as $index => $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $this->product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                    'order' => $lastOrder + $index + 1,
                ]);
            }
        }

        // Sincronizar tags
        $this->product->tags()->sync($this->selectedTags);

        session()->flash('message', 'Producto actualizado correctamente');
        return redirect()->route('admin.products.index');
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        
        // Eliminar archivo físico
        \Storage::disk('public')->delete($image->image_path);
        
        $image->delete();
        
        $this->product->refresh();
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();

        return view('livewire.admin.products.product-edit', compact('categories', 'tags'))
            ->title($this->product->name . ' - Admin');
    }
}
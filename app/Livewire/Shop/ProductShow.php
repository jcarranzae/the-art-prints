<?php

// app/Livewire/Shop/ProductShow.php
namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Product;

#[Layout('layouts.app')]
class ProductShow extends Component
{
    public Product $product;
    public $selectedVariant = null;
    public $quantity = 1;
    public $selectedImageIndex = 0;

    public function mount($slug)
    {
        $this->product = Product::with(['category', 'images', 'variants', 'tags'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Incrementar vistas
        $this->product->incrementViews();
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariant = $variantId;
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // Implementaremos esto en la Fase 3
        $this->dispatch('product-added-to-cart', productId: $this->product->id, quantity: $this->quantity);
        session()->flash('message', 'Producto añadido al carrito');
    }

    public function getTitle(): string
    {
        return $this->product->name . ' - TheArtPrints';
    }

    public function render()
    {
        $relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('livewire.shop.product-show', [
            'relatedProducts' => $relatedProducts,
        ])->title($this->getTitle());
    }
}
<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Services\CartService;

class AddToCart extends Component
{
    public $productId;
    public $variantId = null;
    public $quantity = 1;
    public $showSuccess = false;
    public $showError = false;
    public $message = '';

    public function mount($productId, $variantId = null, $quantity = 1)
    {
        $this->productId = $productId;
        $this->variantId = $variantId;
        $this->quantity = $quantity;
    }

    public function addToCart()
    {
        try {
            $cartService = app(CartService::class);
            $cartService->addToCart($this->productId, $this->quantity, $this->variantId);
            
            $this->dispatch('cart-updated');
            $this->showSuccess = true;
            $this->showError = false;
            $this->message = '¡Producto añadido al carrito!';
            
        } catch (\Exception $e) {
            $this->showSuccess = false;
            $this->showError = true;
            $this->message = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
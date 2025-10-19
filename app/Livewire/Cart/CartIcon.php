<?php

// app/Livewire/Cart/CartIcon.php
namespace App\Livewire\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CartService;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    #[On('cart-updated')]
    public function updateCartCount()
    {
        $cartService = app(CartService::class);
        $this->cartCount = $cartService->getCartCount();
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}
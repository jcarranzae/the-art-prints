<?php

// app/Livewire/Cart/CartPage.php
namespace App\Livewire\Cart;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Carrito - TheArtPrints')]
class CartPage extends Component
{
    public $couponCode = '';
    public $appliedCoupon = null;
    public $couponError = '';

    #[On('cart-updated')]
    public function render()
    {
        $cartService = app(CartService::class);
        $cartItems = $cartService->getCartItems();
        $subtotal = $cartService->getCartTotal();
        
        $discount = 0;
        if ($this->appliedCoupon) {
            $discount = $this->appliedCoupon->calculateDiscount($subtotal);
        }

        $total = $subtotal - $discount;

        return view('livewire.cart.cart-page', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        try {
            $cartService = app(CartService::class);
            $cartService->updateQuantity($cartItemId, $quantity);
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function removeItem($cartItemId)
    {
        $cartService = app(CartService::class);
        $cartService->removeFromCart($cartItemId);
        $this->dispatch('cart-updated');
        session()->flash('message', 'Producto eliminado del carrito');
    }

    public function applyCoupon()
    {
        $this->couponError = '';

        $coupon = \App\Models\Coupon::where('code', strtoupper($this->couponCode))->first();

        if (!$coupon) {
            $this->couponError = 'Cupón no válido';
            return;
        }

        if (!$coupon->isValid()) {
            $this->couponError = 'Este cupón ha expirado o no está disponible';
            return;
        }

        if (Auth::check() && !$coupon->canBeUsedBy(Auth::user())) {
            $this->couponError = 'Ya has usado este cupón el máximo de veces permitido';
            return;
        }

        $cartService = app(CartService::class);
        $subtotal = $cartService->getCartTotal();

        if ($coupon->min_purchase && $subtotal < $coupon->min_purchase) {
            $this->couponError = "Compra mínima de €{$coupon->min_purchase} requerida";
            return;
        }

        $this->appliedCoupon = $coupon;
        session()->flash('message', '¡Cupón aplicado con éxito!');
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
    }
}
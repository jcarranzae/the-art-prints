<?php

// ============================================
// SERVICIO DE CARRITO - app/Services/CartService.php
// ============================================

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    protected function getSessionId(): string
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', Str::random(40));
        }
        
        return session('cart_session_id');
    }

    public function getCartItems()
    {
        $query = CartItem::with(['product.primaryImage', 'variant']);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->get();
    }

    public function addToCart(int $productId, int $quantity = 1, ?int $variantId = null)
    {
        $product = Product::findOrFail($productId);

        // Verificar stock para productos físicos
        if ($product->isPhysical() && $product->track_inventory) {
            if ($variantId) {
                $variant = ProductVariant::findOrFail($variantId);
                if ($variant->stock < $quantity) {
                    throw new \Exception('Stock insuficiente');
                }
            } elseif ($product->stock < $quantity) {
                throw new \Exception('Stock insuficiente');
            }
        }

        $data = [
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            $data['session_id'] = null;
        } else {
            $data['session_id'] = $this->getSessionId();
            $data['user_id'] = null;
        }

        // Buscar si ya existe
        $existingItem = CartItem::where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->where(function ($query) use ($data) {
                if ($data['user_id']) {
                    $query->where('user_id', $data['user_id']);
                } else {
                    $query->where('session_id', $data['session_id']);
                }
            })
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        }

        return CartItem::create($data);
    }

    public function updateQuantity(int $cartItemId, int $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($quantity <= 0) {
            $cartItem->delete();
            return null;
        }

        // Verificar stock
        if ($cartItem->product->isPhysical() && $cartItem->product->track_inventory) {
            $availableStock = $cartItem->variant 
                ? $cartItem->variant->stock 
                : $cartItem->product->stock;

            if ($availableStock < $quantity) {
                throw new \Exception('Stock insuficiente');
            }
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    public function removeFromCart(int $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();
    }

    public function clearCart()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            CartItem::where('session_id', $this->getSessionId())->delete();
        }
    }

    public function getCartTotal(): float
    {
        return $this->getCartItems()->sum(function ($item) {
            return $item->getTotal();
        });
    }

    public function getCartCount(): int
    {
        return $this->getCartItems()->sum('quantity');
    }

    public function mergeGuestCart()
    {
        if (!Auth::check()) {
            return;
        }

        $sessionId = $this->getSessionId();
        
        CartItem::where('session_id', $sessionId)->update([
            'user_id' => Auth::id(),
            'session_id' => null,
        ]);
    }
}
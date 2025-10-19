<?php

// app/Livewire/Checkout/CheckoutPage.php
namespace App\Livewire\Checkout;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Services\CartService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Checkout - TheArtPrints')]
class CheckoutPage extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('nullable|string|max:20')]
    public $phone = '';

    #[Validate('required|string|max:100')]
    public $billing_first_name = '';

    #[Validate('required|string|max:100')]
    public $billing_last_name = '';

    #[Validate('required|string|max:255')]
    public $billing_address = '';

    #[Validate('required|string|max:100')]
    public $billing_city = '';

    #[Validate('nullable|string|max:100')]
    public $billing_state = '';

    #[Validate('required|string|max:20')]
    public $billing_postcode = '';

    #[Validate('required|string|max:100')]
    public $billing_country = 'España';

    public $different_shipping = false;

    #[Validate('nullable|required_if:different_shipping,true|string|max:100')]
    public $shipping_first_name = '';

    #[Validate('nullable|required_if:different_shipping,true|string|max:100')]
    public $shipping_last_name = '';

    #[Validate('nullable|required_if:different_shipping,true|string|max:255')]
    public $shipping_address = '';

    #[Validate('nullable|required_if:different_shipping,true|string|max:100')]
    public $shipping_city = '';

    #[Validate('nullable|string|max:100')]
    public $shipping_state = '';

    #[Validate('nullable|required_if:different_shipping,true|string|max:20')]
    public $shipping_postcode = '';

    #[Validate('nullable|required_if:different_shipping,true|string|max:100')]
    public $shipping_country = '';

    public $payment_method = 'card';
    public $coupon_code = '';
    public $appliedCoupon = null;
    public $notes = '';

    public function mount()
    {
        // Verificar que hay items en el carrito
        $cartService = app(CartService::class);
        if ($cartService->getCartCount() === 0) {
            return redirect()->route('shop.index');
        }

        // Pre-llenar con datos del usuario si está autenticado
        if (auth()->check()) {
            $this->email = auth()->user()->email;
            $this->billing_first_name = auth()->user()->name;
        }
    }

    public function placeOrder()
    {
        $this->validate();

        $cartService = app(CartService::class);
        $cartItems = $cartService->getCartItems();

        if ($cartItems->isEmpty()) {
            session()->flash('error', 'Tu carrito está vacío');
            return redirect()->route('shop.cart');
        }

        DB::beginTransaction();

        try {
            // Calcular totales
            $subtotal = $cartItems->sum(fn($item) => $item->getTotal());
            $discount = 0;
            $couponId = null;

            if ($this->appliedCoupon) {
                $discount = $this->appliedCoupon->calculateDiscount($subtotal);
                $couponId = $this->appliedCoupon->id;
            }

            // Por ahora, shipping y tax son 0
            $shipping_cost = 0;
            $tax = 0;
            $total = $subtotal - $discount + $shipping_cost + $tax;

            // Crear orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'email' => $this->email,
                'phone' => $this->phone,
                'billing_first_name' => $this->billing_first_name,
                'billing_last_name' => $this->billing_last_name,
                'billing_address' => $this->billing_address,
                'billing_city' => $this->billing_city,
                'billing_state' => $this->billing_state,
                'billing_postcode' => $this->billing_postcode,
                'billing_country' => $this->billing_country,
                'different_shipping' => $this->different_shipping,
                'shipping_first_name' => $this->different_shipping ? $this->shipping_first_name : null,
                'shipping_last_name' => $this->different_shipping ? $this->shipping_last_name : null,
                'shipping_address' => $this->different_shipping ? $this->shipping_address : null,
                'shipping_city' => $this->different_shipping ? $this->shipping_city : null,
                'shipping_state' => $this->different_shipping ? $this->shipping_state : null,
                'shipping_postcode' => $this->different_shipping ? $this->shipping_postcode : null,
                'shipping_country' => $this->different_shipping ? $this->shipping_country : null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $this->payment_method,
                'coupon_id' => $couponId,
                'coupon_code' => $this->appliedCoupon?->code,
                'notes' => $this->notes,
            ]);

            // Crear items de la orden
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->getPrice(),
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->getTotal(),
                ]);

                // Reducir stock para productos físicos
                if ($cartItem->product->isPhysical() && $cartItem->product->track_inventory) {
                    if ($cartItem->variant) {
                        $cartItem->variant->decrement('stock', $cartItem->quantity);
                    } else {
                        $cartItem->product->decrement('stock', $cartItem->quantity);
                    }
                }

                // Incrementar ventas
                $cartItem->product->incrementSales($cartItem->quantity);
            }

            // Marcar cupón como usado
            if ($this->appliedCoupon && auth()->check()) {
                $this->appliedCoupon->users()->attach(auth()->id(), [
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);
                $this->appliedCoupon->incrementUsage();
            }

            // Limpiar carrito
            $cartService->clearCart();

            DB::commit();

            // Redirigir a página de confirmación
            session()->flash('message', '¡Pedido realizado con éxito!');
            return redirect()->route('order.confirmation', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function applyCoupon()
    {
        if (empty($this->coupon_code)) {
            return;
        }

        $coupon = \App\Models\Coupon::where('code', strtoupper($this->coupon_code))->first();

        if (!$coupon || !$coupon->isValid()) {
            session()->flash('coupon_error', 'Cupón no válido');
            return;
        }

        $this->appliedCoupon = $coupon;
        session()->flash('coupon_success', '¡Cupón aplicado!');
    }

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

        return view('livewire.checkout.checkout-page', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);
    }
}
<?php
// resources/views/livewire/cart/cart-page.blade.php
?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

        @if ($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de productos -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="bg-white rounded-lg shadow p-6 flex items-center gap-6">
                            <!-- Imagen -->
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-4xl">🎨</span>
                            </div>

                            <!-- Info -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">
                                    {{ $item->product->name }}
                                </h3>
                                @if ($item->variant)
                                    <p class="text-sm text-gray-600">{{ $item->variant->name }}</p>
                                @endif
                                <p class="text-indigo-600 font-bold mt-2">
                                    €{{ number_format($item->getPrice(), 2) }}
                                </p>
                            </div>

                            <!-- Cantidad -->
                            <div class="flex items-center border rounded-lg">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="px-3 py-2 hover:bg-gray-100">
                                    -
                                </button>
                                <span class="px-4 py-2 font-semibold">{{ $item->quantity }}</span>
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                    class="px-3 py-2 hover:bg-gray-100">
                                    +
                                </button>
                            </div>

                            <!-- Total y eliminar -->
                            <div class="text-right">
                                <p class="font-bold text-lg text-gray-900 mb-2">
                                    €{{ number_format($item->getTotal(), 2) }}
                                </p>
                                <button wire:click="removeItem({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del pedido</h2>

                        <!-- Cupón -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Código de descuento</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="Código"
                                    class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <button wire:click="applyCoupon"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">
                                    Aplicar
                                </button>
                            </div>
                            @if ($couponError)
                                <p class="text-red-600 text-sm mt-1">{{ $couponError }}</p>
                            @endif
                            @if ($appliedCoupon)
                                <div class="mt-2 flex items-center justify-between text-sm">
                                    <span class="text-green-600 font-semibold">✓ Cupón aplicado</span>
                                    <button wire:click="removeCoupon"
                                        class="text-red-600 hover:text-red-800">Quitar</button>
                                </div>
                            @endif
                        </div>

                        <!-- Totales -->
                        <div class="space-y-3 border-t pt-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">€{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if ($discount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Descuento</span>
                                    <span class="font-semibold">-€{{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-xl font-bold border-t pt-3">
                                <span>Total</span>
                                <span class="text-indigo-600">€{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}"
                            class="block w-full mt-6 bg-indigo-600 text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Proceder al pago
                        </a>

                        <a href="{{ route('shop.index') }}"
                            class="block w-full mt-3 text-center text-indigo-600 hover:text-indigo-800 font-semibold">
                            Seguir comprando
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Tu carrito está vacío</h2>
                <p class="text-gray-500 mb-6">Añade productos para empezar tu compra</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700">
                    Explorar tienda
                </a>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>

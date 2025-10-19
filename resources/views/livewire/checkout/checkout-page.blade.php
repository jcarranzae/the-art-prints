<?php
// resources/views/livewire/checkout/checkout-page.blade.php - PARTE 1
?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

        <form wire:submit="placeOrder">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulario -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información de contacto -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Información de contacto</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('email')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="text" wire:model="phone"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Dirección de facturación -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Dirección de facturación</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input type="text" wire:model="billing_first_name"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_first_name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                                <input type="text" wire:model="billing_last_name"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_last_name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <input type="text" wire:model="billing_address"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_address')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                                <input type="text" wire:model="billing_city"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_city')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                <input type="text" wire:model="billing_state"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                                <input type="text" wire:model="billing_postcode"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_postcode')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">País *</label>
                                <input type="text" wire:model="billing_country"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('billing_country')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección de envío diferente -->
                        <div class="mt-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="different_shipping" class="mr-2">
                                <span class="text-sm text-gray-700">Enviar a una dirección diferente</span>
                            </label>
                        </div>
                    </div>

                    <!-- Dirección de envío (si es diferente) -->
                    @if ($different_shipping)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Dirección de envío</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                    <input type="text" wire:model="shipping_first_name"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                                    <input type="text" wire:model="shipping_last_name"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                    <input type="text" wire:model="shipping_address"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                                    <input type="text" wire:model="shipping_city"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                                    <input type="text" wire:model="shipping_state"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                                    <input type="text" wire:model="shipping_postcode"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">País *</label>
                                    <input type="text" wire:model="shipping_country"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notas adicionales -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notas del pedido (opcional)</label>
                        <textarea wire:model="notes" rows="3"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="Notas sobre tu pedido, ej. instrucciones especiales de entrega"></textarea>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del pedido</h2>

                        <!-- Productos -->
                        <div class="space-y-3 mb-4">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $item->product->name }} ×
                                        {{ $item->quantity }}</span>
                                    <span class="font-semibold">€{{ number_format($item->getTotal(), 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Cupón -->
                        <div class="mb-4 pb-4 border-b">
                            <div class="flex gap-2">
                                <input type="text" wire:model="coupon_code" placeholder="Código de descuento"
                                    class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                <button type="button" wire:click="applyCoupon"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm">
                                    Aplicar
                                </button>
                            </div>
                            @if (session()->has('coupon_error'))
                                <p class="text-red-600 text-sm mt-1">{{ session('coupon_error') }}</p>
                            @endif
                            @if ($appliedCoupon)
                                <p class="text-green-600 text-sm mt-1">✓ Cupón aplicado</p>
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

                        <button type="submit"
                            class="w-full mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Realizar pedido
                        </button>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            Al realizar el pedido, aceptas nuestros términos y condiciones
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

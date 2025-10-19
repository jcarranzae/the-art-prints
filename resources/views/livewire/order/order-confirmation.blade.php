<?php
// resources/views/livewire/order/order-confirmation.blade.php
?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">✅</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">¡Pedido Confirmado!</h1>
            <p class="text-gray-600 mb-8">
                Gracias por tu compra. Tu pedido ha sido procesado exitosamente.
            </p>

            <div class="bg-indigo-50 rounded-lg p-6 mb-8">
                <p class="text-sm text-gray-600 mb-2">Número de pedido</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $order->order_number }}</p>
            </div>

            <!-- Detalles del pedido -->
            <div class="text-left space-y-6 mb-8">
                <div>
                    <h2 class="font-bold text-lg text-gray-900 mb-3">Resumen del pedido</h2>
                    @foreach ($order->items as $item)
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span class="font-semibold">€{{ number_format($item->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 pt-4 border-t-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>€{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if ($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Descuento</span>
                            <span>-€{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold pt-2 border-t">
                        <span>Total</span>
                        <span class="text-indigo-600">€{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Dirección de envío</h3>
                    <p class="text-sm text-gray-600">
                        {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                        {{ $order->billing_address }}<br>
                        {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                        {{ $order->billing_country }}
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('shop.index') }}"
                    class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Seguir comprando
                </a>
                <a href="#"
                    class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Ver mis pedidos
                </a>
            </div>
        </div>
    </div>
</div>

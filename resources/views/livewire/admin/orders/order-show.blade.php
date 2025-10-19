<?php
// resources/views/livewire/admin/orders/order-show.blade.php
?>
<div>
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800">
            ← Volver a pedidos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalles principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del pedido -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold">Pedido {{ $order->order_number }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-indigo-600">€{{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-4">Productos</h3>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-4 pb-4 border-b">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded flex items-center justify-center flex-shrink-0">
                                    <span class="text-2xl">🎨</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $item->product_name }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Precio unitario:
                                        €{{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">€{{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totales -->
                    <div class="mt-6 space-y-2 border-t pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">€{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if ($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Descuento @if ($order->coupon_code)
                                        ({{ $order->coupon_code }})
                                    @endif
                                </span>
                                <span class="font-semibold">-€{{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->shipping_cost > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-semibold">€{{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-xl font-bold border-t pt-2">
                            <span>Total</span>
                            <span class="text-indigo-600">€{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Direcciones -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Información de Envío</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Dirección de Facturación</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                            <p>{{ $order->billing_address }}</p>
                            <p>{{ $order->billing_city }}, {{ $order->billing_postcode }}</p>
                            <p>{{ $order->billing_country }}</p>
                        </div>
                    </div>

                    @if ($order->different_shipping)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Dirección de Envío</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                                <p>{{ $order->shipping_address }}</p>
                                <p>{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                                <p>{{ $order->shipping_country }}</p>
                            </div>
                        </div>
                    @else
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Dirección de Envío</h4>
                            <p class="text-sm text-gray-600">Misma que facturación</p>
                        </div>
                    @endif
                </div>

                @if ($order->notes)
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-medium text-gray-900 mb-2">Notas del Pedido</h4>
                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Cliente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Cliente</h3>
                <div class="space-y-2">
                    <p class="font-medium">{{ $order->user->name ?? 'Invitado' }}</p>
                    <p class="text-sm text-gray-600">{{ $order->email }}</p>
                    @if ($order->phone)
                        <p class="text-sm text-gray-600">{{ $order->phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Estados -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Estado del Pedido</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select wire:change="updateStatus($event.target.value)"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente
                            </option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                Procesando</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completado
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
                        <select wire:change="updatePaymentStatus($event.target.value)"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>
                                Pendiente</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Pagado
                            </option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Fallido
                            </option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>
                                Reembolsado</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t text-sm text-gray-600">
                        <p>Método de pago: <span class="font-medium">{{ ucfirst($order->payment_method) }}</span></p>
                        @if ($order->paid_at)
                            <p class="mt-1">Pagado: {{ $order->paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>
</div>

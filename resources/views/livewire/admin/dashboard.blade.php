<?php
// resources/views/livewire/admin/dashboard.blade.php
?>
<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Productos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pedidos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Ingresos Totales</p>
                    <p class="text-3xl font-bold text-gray-900">€{{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Clientes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pedidos recientes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Pedidos Recientes</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach ($recentOrders as $order)
                        <div class="flex items-center justify-between border-b pb-3">
                            <div>
                                <p class="font-semibold">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->name ?? $order->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-indigo-600">€{{ number_format($order->total, 2) }}</p>
                                <span
                                    class="text-xs px-2 py-1 rounded {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.orders.index') }}"
                    class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 font-semibold">
                    Ver todos los pedidos →
                </a>
            </div>
        </div>

        <!-- Productos más vendidos -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Productos Más Vendidos</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach ($topProducts as $product)
                        <div class="flex items-center justify-between border-b pb-3">
                            <div class="flex-1">
                                <p class="font-semibold">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->sales_count }} ventas</p>
                            </div>
                            <p class="font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.products.index') }}"
                    class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 font-semibold">
                    Ver todos los productos →
                </a>
            </div>
        </div>
    </div>
</div>

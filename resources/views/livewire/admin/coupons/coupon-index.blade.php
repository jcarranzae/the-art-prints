<?php
// resources/views/livewire/admin/coupons/coupon-index.blade.php
?>
<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Cupones de Descuento</h1>
        <a href="{{ route('admin.coupons.create') }}"
            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold">
            + Nuevo Cupón
        </a>
    </div>

    <!-- Búsqueda -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar cupones..."
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Válido hasta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-lg font-mono">{{ $coupon->code }}</div>
                            @if ($coupon->description)
                                <div class="text-sm text-gray-500">{{ $coupon->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs rounded {{ $coupon->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $coupon->type === 'percentage' ? 'Porcentaje' : 'Fijo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '€' . number_format($coupon->value, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if ($coupon->expires_at)
                                <span class="{{ $coupon->expires_at->isPast() ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $coupon->expires_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">Sin límite</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatus({{ $coupon->id }})"
                                class="px-2 py-1 text-xs rounded {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $coupon->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Editar
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="deleteCoupon({{ $coupon->id }})" wire:confirm="¿Eliminar este cupón?"
                                class="text-red-600 hover:text-red-900">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No hay cupones disponibles
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>

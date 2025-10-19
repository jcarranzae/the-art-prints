<?php
// ============================================
// VISTAS BLADE
// ============================================

// resources/views/livewire/admin/coupons/coupon-create.blade.php
?>
<div>
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-indigo-600 hover:text-indigo-800">
            ← Volver a cupones
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Crear Nuevo Cupón</h2>
        </div>

        <form wire:submit="save" class="p-6">
            <div class="max-w-3xl space-y-6">
                <!-- Código del cupón -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Información del Cupón</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Código del Cupón *</label>
                            <input type="text" wire:model.blur="code"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-lg"
                                placeholder="DESCUENTO10" maxlength="50">
                            <p class="text-sm text-gray-500 mt-1">El código se convertirá automáticamente a mayúsculas
                            </p>
                            @error('code')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea wire:model="description" rows="2"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Descripción breve del cupón"></textarea>
                            @error('description')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tipo y valor del descuento -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Descuento</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Descuento *</label>
                            <div class="space-y-2">
                                <label
                                    class="flex items-center cursor-pointer p-3 border rounded-lg {{ $type === 'percentage' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model.live="type" value="percentage"
                                        class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <div class="font-medium">Porcentaje</div>
                                        <div class="text-sm text-gray-500">Ej: 10% de descuento</div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center cursor-pointer p-3 border rounded-lg {{ $type === 'fixed' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300' }}">
                                    <input type="radio" wire:model.live="type" value="fixed"
                                        class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <div class="font-medium">Cantidad Fija</div>
                                        <div class="text-sm text-gray-500">Ej: €5 de descuento</div>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Valor del Descuento *
                            </label>
                            <div class="relative">
                                @if ($type === 'percentage')
                                    <input type="number" step="0.01" wire:model="value"
                                        class="w-full pr-12 pl-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="10" max="100">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                @else
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" step="0.01" wire:model="value"
                                        class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="5.00">
                                @endif
                            </div>
                            @error('value')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Restricciones -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Restricciones</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Compra Mínima</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">€</span>
                                <input type="number" step="0.01" wire:model="min_purchase"
                                    class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="0.00">
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Importe mínimo requerido para usar el cupón</p>
                            @error('min_purchase')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Límite Total de Usos</label>
                                <input type="number" wire:model="usage_limit"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Ilimitado">
                                <p class="text-sm text-gray-500 mt-1">Número máximo de veces que se puede usar</p>
                                @error('usage_limit')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Límite por Usuario</label>
                                <input type="number" wire:model="usage_per_user"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Ilimitado">
                                <p class="text-sm text-gray-500 mt-1">Veces que puede usar un mismo usuario</p>
                                @error('usage_per_user')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas de validez -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Período de Validez</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio</label>
                            <input type="date" wire:model="starts_at"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Deja vacío para comenzar inmediatamente</p>
                            @error('starts_at')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Expiración</label>
                            <input type="date" wire:model="expires_at"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Deja vacío para que no expire</p>
                            @error('expires_at')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Estado</h3>

                    <label
                        class="flex items-center cursor-pointer p-4 border rounded-lg {{ $is_active ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                        <input type="checkbox" wire:model="is_active"
                            class="mr-3 rounded text-green-600 focus:ring-green-500">
                        <div>
                            <div class="font-medium">Cupón Activo</div>
                            <div class="text-sm text-gray-600">Los usuarios podrán usar este cupón si está activo</div>
                        </div>
                    </label>
                </div>

                <!-- Preview del cupón -->
                <div
                    class="bg-gradient-to-r from-indigo-100 to-purple-100 rounded-lg p-6 border-2 border-dashed border-indigo-300">
                    <h4 class="font-semibold text-gray-700 mb-2">Vista Previa del Cupón</h4>
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="font-mono text-2xl font-bold text-center text-indigo-600 mb-2">
                            {{ $code ?: 'CODIGO' }}
                        </div>
                        <div class="text-center text-3xl font-bold text-gray-900 mb-1">
                            @if ($type === 'percentage')
                                {{ $value ?: '0' }}% OFF
                            @else
                                €{{ number_format($value ?: 0, 2) }} OFF
                            @endif
                        </div>
                        @if ($description)
                            <div class="text-center text-sm text-gray-600">{{ $description }}</div>
                        @endif
                        @if ($min_purchase)
                            <div class="text-center text-xs text-gray-500 mt-2">
                                Compra mínima: €{{ number_format($min_purchase, 2) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('admin.coupons.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Crear Cupón</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

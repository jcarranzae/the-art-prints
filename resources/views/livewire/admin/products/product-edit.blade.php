<?php
// resources/views/livewire/admin/products/product-edit.blade.php
?>
<div>
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">
            ← Volver a productos
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Editar Producto: {{ $product->name }}</h2>
        </div>

        <form wire:submit="update" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Columna izquierda -->
                <div class="space-y-6">
                    <!-- Información básica -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Información Básica</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto
                                    *</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @error('name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción Corta</label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                                @error('description')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción
                                    Detallada</label>
                                <textarea wire:model="long_description" rows="5"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                                <select wire:model="category_id"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Sin categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Etiquetas</label>
                                <div class="flex flex-wrap gap-2 p-4 border rounded-lg">
                                    @foreach ($tags as $tag)
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                                                class="mr-2 rounded text-indigo-600">
                                            <span class="text-sm">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imágenes existentes -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Imágenes Actuales</h3>

                        @if ($product->images->count() > 0)
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                @foreach ($product->images as $image)
                                    <div class="relative group">
                                        <div
                                            class="w-full h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center">
                                            <span class="text-4xl">🎨</span>
                                        </div>
                                        @if ($image->is_primary)
                                            <span
                                                class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded">
                                                Principal
                                            </span>
                                        @endif
                                        <button type="button" wire:click="deleteImage({{ $image->id }})"
                                            wire:confirm="¿Eliminar esta imagen?"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No hay imágenes</p>
                        @endif

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Añadir Más Imágenes</label>
                            <input type="file" wire:model="newImages" multiple accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">

                            @if ($newImages)
                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    @foreach ($newImages as $image)
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="w-full h-32 object-cover rounded-lg">
                                    @endforeach
                                </div>
                            @endif

                            <div wire:loading wire:target="newImages" class="text-sm text-gray-600 mt-2">
                                Subiendo imágenes...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - igual que en create -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Precio</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" step="0.01" wire:model="price"
                                        class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                @error('price')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Comparativo</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" step="0.01" wire:model="compare_price"
                                        class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tipo de Producto</h3>
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="type" value="digital" class="mr-2">
                                <span>Digital</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="type" value="physical" class="mr-2">
                                <span>Físico</span>
                            </label>
                        </div>
                    </div>

                    @if ($type === 'digital')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Límite de Descargas</label>
                            <input type="number" wire:model="download_limit"
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    @endif

                    @if ($type === 'physical')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                <input type="text" wire:model="sku" class="w-full px-4 py-2 border rounded-lg">
                            </div>

                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="track_inventory" class="mr-2 rounded">
                                <span>Controlar inventario</span>
                            </label>

                            @if ($track_inventory)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                                    <input type="number" wire:model="stock"
                                        class="w-full px-4 py-2 border rounded-lg">
                                </div>
                            @endif
                        </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Estado</h3>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="mr-2 rounded">
                                <span>Producto activo</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_featured" class="mr-2 rounded">
                                <span>Producto destacado</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Actualizar Producto</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

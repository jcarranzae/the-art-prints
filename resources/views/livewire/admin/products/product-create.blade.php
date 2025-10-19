<?php
// resources/views/livewire/admin/products/product-create.blade.php
?>
<div>
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">
            ← Volver a productos
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Crear Nuevo Producto</h2>
        </div>

        <form wire:submit="save" class="p-6">
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
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Nombre del producto">
                                @error('name')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción Corta</label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Descripción breve para el listado"></textarea>
                                @error('description')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción
                                    Detallada</label>
                                <textarea wire:model="long_description" rows="5"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Descripción completa del producto"></textarea>
                                @error('long_description')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                                <select wire:model="category_id"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Etiquetas</label>
                                <div class="flex flex-wrap gap-2 p-4 border rounded-lg">
                                    @foreach ($tags as $tag)
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                                                class="mr-2 rounded text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imágenes -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Imágenes</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subir Imágenes</label>
                            <input type="file" wire:model="images" multiple accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('images.*')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                            <p class="text-sm text-gray-500 mt-2">La primera imagen será la principal</p>

                            @if ($images)
                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    @foreach ($images as $image)
                                        <div class="relative">
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="w-full h-32 object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div wire:loading wire:target="images" class="text-sm text-gray-600 mt-2">
                                Subiendo imágenes...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="space-y-6">
                    <!-- Precio -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Precio</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" step="0.01" wire:model="price"
                                        class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="0.00">
                                </div>
                                @error('price')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Comparativo
                                    (Antes)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" step="0.01" wire:model="compare_price"
                                        class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="0.00">
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Muestra el precio tachado y el descuento</p>
                                @error('compare_price')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de producto -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tipo de Producto</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="type" value="digital"
                                        class="mr-2 text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-medium">Digital</span>
                                </label>
                                <p class="text-sm text-gray-500 ml-6">Producto descargable</p>
                            </div>

                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="type" value="physical"
                                        class="mr-2 text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-medium">Físico</span>
                                </label>
                                <p class="text-sm text-gray-500 ml-6">Producto con envío físico</p>
                            </div>
                        </div>
                        @error('type')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Configuración específica -->
                    @if ($type === 'digital')
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Configuración Digital</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Límite de Descargas</label>
                                <input type="number" wire:model="download_limit"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    placeholder="3">
                                <p class="text-sm text-gray-500 mt-1">Deja vacío para descargas ilimitadas</p>
                                @error('download_limit')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if ($type === 'physical')
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Inventario</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                                    <input type="text" wire:model="sku"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                        placeholder="SKU-001">
                                    @error('sku')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="track_inventory"
                                            class="mr-2 rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="font-medium">Controlar inventario</span>
                                    </label>
                                </div>

                                @if ($track_inventory)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock
                                            Disponible</label>
                                        <input type="number" wire:model="stock"
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                            placeholder="0">
                                        @error('stock')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Estado -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Estado</h3>

                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="mr-2 rounded text-indigo-600 focus:ring-indigo-500">
                                <span>Producto activo (visible en tienda)</span>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_featured"
                                    class="mr-2 rounded text-indigo-600 focus:ring-indigo-500">
                                <span>Producto destacado</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Crear Producto</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>

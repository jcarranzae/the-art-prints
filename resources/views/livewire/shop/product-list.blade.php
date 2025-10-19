<?php
// resources/views/livewire/shop/product-list.blade.php
?>
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Ilustraciones Originales</h1>
            <p class="text-xl opacity-90">Descubre arte digital único creado con pasión</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Barra de búsqueda y filtros -->
        <div class="mb-8 space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Búsqueda -->
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar ilustraciones..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Ordenar -->
                <select wire:model.live="sortBy"
                    class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="newest">Más recientes</option>
                    <option value="popular">Más populares</option>
                    <option value="featured">Destacados</option>
                    <option value="price_asc">Precio: menor a mayor</option>
                    <option value="price_desc">Precio: mayor a menor</option>
                </select>

                <!-- Toggle filtros móvil -->
                <button wire:click="$toggle('showFilters')"
                    class="md:hidden px-4 py-3 bg-indigo-600 text-white rounded-lg font-semibold">
                    Filtros
                </button>
            </div>

            <!-- Filtros activos -->
            @if ($search || $categoryFilter || !empty($selectedTags) || $typeFilter)
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-600">Filtros activos:</span>

                    @if ($search)
                        <span
                            class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm flex items-center gap-2">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="hover:text-indigo-600">×</button>
                        </span>
                    @endif

                    @if ($categoryFilter)
                        <span
                            class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm flex items-center gap-2">
                            {{ $categories->find($categoryFilter)->name }}
                            <button wire:click="$set('categoryFilter', '')" class="hover:text-indigo-600">×</button>
                        </span>
                    @endif

                    @foreach ($selectedTags as $tagId)
                        <span
                            class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm flex items-center gap-2">
                            {{ $tags->find($tagId)->name }}
                            <button wire:click="toggleTag({{ $tagId }})" class="hover:text-indigo-600">×</button>
                        </span>
                    @endforeach

                    <button wire:click="clearFilters"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                        Limpiar todo
                    </button>
                </div>
            @endif
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar de filtros -->
            <aside class="w-full md:w-64 space-y-6" x-show="$wire.showFilters || window.innerWidth >= 768" x-cloak>
                <!-- Categorías -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Categorías</h3>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="categoryFilter" value=""
                                class="mr-2 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-gray-700">Todas</span>
                        </label>
                        @foreach ($categories as $category)
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="categoryFilter" value="{{ $category->id }}"
                                    class="mr-2 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tipo de producto -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Tipo</h3>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="typeFilter" value=""
                                class="mr-2 text-indigo-600">
                            <span class="text-gray-700">Todos</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="typeFilter" value="digital"
                                class="mr-2 text-indigo-600">
                            <span class="text-gray-700">Digital</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="typeFilter" value="physical"
                                class="mr-2 text-indigo-600">
                            <span class="text-gray-700">Físico</span>
                        </label>
                    </div>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Etiquetas</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <button wire:click="toggleTag({{ $tag->id }})"
                                class="px-3 py-1 rounded-full text-sm transition
                                    {{ in_array($tag->id, $selectedTags)
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ $tag->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Rango de precio -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Precio</h3>
                    <div class="space-y-3">
                        <input type="number" wire:model.live.debounce.500ms="priceMin" placeholder="Mínimo"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <input type="number" wire:model.live.debounce.500ms="priceMax" placeholder="Máximo"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </aside>

            <!-- Grid de productos -->
            <div class="flex-1">
                @if ($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <a href="{{ route('shop.product', $product->slug) }}"
                                class="group bg-white rounded-lg shadow hover:shadow-xl transition overflow-hidden">
                                <!-- Imagen -->
                                <div class="aspect-square bg-gray-200 overflow-hidden">
                                    @if ($product->primaryImage)
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                            <span class="text-6xl">🎨</span>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-400">Sin imagen</span>
                                        </div>
                                    @endif

                                    @if ($product->is_featured)
                                        <div
                                            class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">
                                            Destacado
                                        </div>
                                    @endif

                                    @if ($product->hasDiscount())
                                        <div
                                            class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                            -{{ $product->discountPercentage() }}%
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="p-4">
                                    <h3
                                        class="font-semibold text-gray-900 group-hover:text-indigo-600 transition mb-2">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {{ $product->description }}
                                    </p>

                                    <!-- Tags -->
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach ($product->tags->take(3) as $tag)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Precio -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if ($product->hasDiscount())
                                                <span
                                                    class="text-lg font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</span>
                                                <span
                                                    class="text-sm text-gray-400 line-through ml-2">€{{ number_format($product->compare_price, 2) }}</span>
                                            @else
                                                <span
                                                    class="text-lg font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $product->type === 'digital' ? 'Digital' : 'Físico' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron productos</h3>
                        <p class="text-gray-500 mb-4">Intenta ajustar tus filtros de búsqueda</p>
                        <button wire:click="clearFilters"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
                            Limpiar filtros
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

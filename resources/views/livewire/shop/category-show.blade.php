<?php
// resources/views/livewire/shop/category-show.blade.php
?>
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $category->name }}</h1>
            @if ($category->description)
                <p class="text-xl opacity-90">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Subcategorías si existen -->
        @if ($category->children->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4">Subcategorías</h2>
                <div class="flex gap-4 overflow-x-auto pb-2">
                    @foreach ($category->children as $child)
                        <a href="{{ route('shop.category', $child->slug) }}"
                            class="px-6 py-3 bg-white rounded-lg shadow hover:shadow-lg transition whitespace-nowrap">
                            {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ordenar -->
        <div class="mb-6 flex justify-end">
            <select wire:model.live="sortBy"
                class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                <option value="newest">Más recientes</option>
                <option value="popular">Más populares</option>
                <option value="price_asc">Precio: menor a mayor</option>
                <option value="price_desc">Precio: mayor a menor</option>
            </select>
        </div>

        <!-- Grid de productos -->
        @if ($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <a href="{{ route('shop.product', $product->slug) }}"
                        class="group bg-white rounded-lg shadow hover:shadow-xl transition overflow-hidden">
                        <div
                            class="aspect-square bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <span class="text-6xl">🎨</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 mb-2">
                                {{ $product->name }}
                            </h3>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-lg font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    {{ $product->type === 'digital' ? 'Digital' : 'Físico' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay productos en esta categoría</h3>
                <p class="text-gray-500">Vuelve pronto para ver nuevas ilustraciones</p>
            </div>
        @endif
    </div>
</div>

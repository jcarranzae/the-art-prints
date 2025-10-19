<?php
// resources/views/livewire/shop/product-show.blade.php
?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Inicio</a></li>
                <li>/</li>
                @if ($product->category)
                    <li><a href="{{ route('shop.category', $product->category->slug) }}"
                            class="hover:text-indigo-600">{{ $product->category->name }}</a></li>
                    <li>/</li>
                @endif
                <li class="text-gray-900 font-semibold">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Galería de imágenes -->
            <div class="space-y-4">
                <div
                    class="aspect-square bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg overflow-hidden flex items-center justify-center">
                    <span class="text-9xl">🎨</span>
                </div>

                @if ($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($product->images as $index => $image)
                            <button wire:click="selectImage({{ $index }})"
                                class="aspect-square bg-gray-200 rounded-lg overflow-hidden border-2 
                                    {{ $selectedImageIndex === $index ? 'border-indigo-600' : 'border-transparent' }}">
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                                    <span class="text-2xl">🎨</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Información del producto -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                    @if ($product->category)
                        <a href="{{ route('shop.category', $product->category->slug) }}"
                            class="text-indigo-600 hover:text-indigo-800">
                            {{ $product->category->name }}
                        </a>
                    @endif
                </div>

                <!-- Precio -->
                <div class="flex items-baseline gap-4">
                    @if ($product->hasDiscount())
                        <span class="text-4xl font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</span>
                        <span
                            class="text-2xl text-gray-400 line-through">€{{ number_format($product->compare_price, 2) }}</span>
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            -{{ $product->discountPercentage() }}%
                        </span>
                    @else
                        <span
                            class="text-4xl font-bold text-indigo-600">€{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Descripción -->
                <div class="prose max-w-none">
                    <p class="text-gray-600">{{ $product->description }}</p>
                    @if ($product->long_description)
                        <p class="text-gray-700 mt-4">{{ $product->long_description }}</p>
                    @endif
                </div>

                <!-- Tags -->
                @if ($product->tags->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Etiquetas:</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($product->tags as $tag)
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Variantes (si existen) -->
                @if ($product->variants->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Selecciona una opción:</h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($product->variants as $variant)
                                <button wire:click="selectVariant({{ $variant->id }})"
                                    class="px-4 py-3 border-2 rounded-lg text-sm font-semibold transition
                                        {{ $selectedVariant === $variant->id
                                            ? 'border-indigo-600 bg-indigo-50 text-indigo-900'
                                            : 'border-gray-300 hover:border-gray-400' }}
                                        {{ !$variant->inStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ !$variant->inStock() ? 'disabled' : '' }}>
                                    {{ $variant->name }}
                                    @if (!$variant->inStock())
                                        <span class="block text-xs text-red-600">Agotado</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Cantidad y añadir al carrito -->
                <div class="space-y-4">
                    @if ($product->isPhysical() && $product->track_inventory)
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-semibold text-gray-900">Cantidad:</label>
                            <div class="flex items-center border rounded-lg">
                                <button wire:click="decrementQuantity" class="px-4 py-2 hover:bg-gray-100 transition">
                                    -
                                </button>
                                <span class="px-6 py-2 font-semibold">{{ $quantity }}</span>
                                <button wire:click="incrementQuantity" class="px-4 py-2 hover:bg-gray-100 transition">
                                    +
                                </button>
                            </div>
                            <span class="text-sm text-gray-600">{{ $product->stock }} disponibles</span>
                        </div>
                    @endif

                    <button wire:click="addToCart"
                        class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ !$product->inStock() ? 'disabled' : '' }}>
                        @if ($product->inStock())
                            {{ $product->isDigital() ? 'Comprar ahora' : 'Añadir al carrito' }}
                        @else
                            Agotado
                        @endif
                    </button>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>

                <!-- Información adicional -->
                <div class="border-t pt-6 space-y-3 text-sm text-gray-600">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>{{ $product->isDigital() ? 'Descarga instantánea después del pago' : 'Envío en 3-5 días laborables' }}</span>
                    </div>
                    @if ($product->isDigital() && $product->download_limit)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Hasta {{ $product->download_limit }} descargas permitidas</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Pago seguro garantizado</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos relacionados -->
        @if ($relatedProducts->count() > 0)
            <div class="border-t pt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Productos relacionados</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $related)
                        <a href="{{ route('shop.product', $related->slug) }}" class="group">
                            <div
                                class="aspect-square bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                                <span class="text-6xl">🎨</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 mb-1">
                                {{ $related->name }}</h3>
                            <p class="text-indigo-600 font-bold">€{{ number_format($related->price, 2) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

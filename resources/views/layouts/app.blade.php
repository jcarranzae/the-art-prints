{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TheArtPrints - Ilustraciones Originales' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo y enlaces principales -->
                    <div class="flex items-center space-x-8">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center">
                            <span
                                class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                TheArtPrints
                            </span>
                        </a>

                        <!-- Enlaces de navegación (desktop) -->
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('home') }}"
                                class="text-gray-700 hover:text-indigo-600 font-medium transition {{ request()->routeIs('home') ? 'text-indigo-600' : '' }}">
                                Inicio
                            </a>
                            <a href="{{ route('shop.index') }}"
                                class="text-gray-700 hover:text-indigo-600 font-medium transition {{ request()->routeIs('shop.index') ? 'text-indigo-600' : '' }}">
                                Tienda
                            </a>

                            <!-- Dropdown de categorías -->
                            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                                <button @click="open = !open"
                                    class="text-gray-700 hover:text-indigo-600 font-medium transition flex items-center">
                                    Categorías
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                    <div class="py-2">
                                        @php
                                            $categories = \App\Models\Category::whereNull('parent_id')
                                                ->where('is_active', true)
                                                ->orderBy('order')
                                                ->get();
                                        @endphp
                                        @foreach ($categories as $category)
                                            <a href="{{ route('shop.category', $category->slug) }}"
                                                class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                                Sobre mí
                            </a>
                            <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                                Contacto
                            </a>
                        </div>
                    </div>

                    <!-- Acciones de usuario -->
                    <div class="flex items-center space-x-4">
                        <!-- Búsqueda rápida (desktop) -->
                        <div class="hidden lg:block">
                            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                                <input type="text" name="buscar" placeholder="Buscar..."
                                    class="w-64 px-4 py-2 pr-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <button type="submit"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Carrito -->
                        <a href="#" class="relative text-gray-700 hover:text-indigo-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <!-- Badge del carrito - lo activaremos en fase 3 -->
                            <span
                                class="hidden absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                0
                            </span>
                        </a>

                        <!-- Usuario -->
                        @auth
                            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition
                                    class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                    <div class="py-2">
                                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                            {{ auth()->user()->name }}
                                        </div>
                                        <a href="#"
                                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            Mi cuenta
                                        </a>
                                        <a href="#"
                                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            Mis pedidos
                                        </a>
                                        @if (auth()->user()->is_admin ?? false)
                                            <a href="#"
                                                class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                                Panel Admin
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                                Cerrar sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-indigo-600 transition font-medium">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                Registrarse
                            </a>
                        @endauth

                        <!-- Menú móvil hamburguesa -->
                        <button @click="$dispatch('toggle-mobile-menu')"
                            class="md:hidden text-gray-700 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menú móvil -->
            <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" @click.away="open = false"
                x-show="open" x-transition class="md:hidden border-t" style="display: none;">
                <div class="px-4 py-3 space-y-3">
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-indigo-600 font-medium">
                        Inicio
                    </a>
                    <a href="{{ route('shop.index') }}"
                        class="block text-gray-700 hover:text-indigo-600 font-medium">
                        Tienda
                    </a>

                    <!-- Categorías móvil -->
                    <div class="pl-4 space-y-2">
                        @php
                            $categories = \App\Models\Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->get();
                        @endphp
                        @foreach ($categories as $category)
                            <a href="{{ route('shop.category', $category->slug) }}"
                                class="block text-sm text-gray-600 hover:text-indigo-600">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">
                        Sobre mí
                    </a>
                    <a href="#" class="block text-gray-700 hover:text-indigo-600 font-medium">
                        Contacto
                    </a>

                    <!-- Búsqueda móvil -->
                    <form action="{{ route('shop.index') }}" method="GET" class="pt-3">
                        <input type="text" name="buscar" placeholder="Buscar productos..."
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Sobre la tienda -->
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-white text-xl font-bold mb-4">TheArtPrints</h3>
                        <p class="text-gray-400 mb-4">
                            Ilustraciones digitales originales creadas con pasión. Descubre arte único para tus
                            proyectos y decoración.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Enlaces rápidos -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Enlaces rápidos</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Tienda</a>
                            </li>
                            <li><a href="#" class="hover:text-white transition">Sobre mí</a></li>
                            <li><a href="#" class="hover:text-white transition">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                        </ul>
                    </div>

                    <!-- Información -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Soporte</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-white transition">Preguntas frecuentes</a></li>
                            <li><a href="#" class="hover:text-white transition">Envíos</a></li>
                            <li><a href="#" class="hover:text-white transition">Devoluciones</a></li>
                            <li><a href="#" class="hover:text-white transition">Términos y condiciones</a></li>
                            <li><a href="#" class="hover:text-white transition">Política de privacidad</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500">
                    <p>&copy; {{ date('Y') }} TheArtPrints. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>

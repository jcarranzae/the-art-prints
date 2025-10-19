<?php
// resources/views/livewire/cart/add-to-cart.blade.php
?>
<div>
    <button wire:click="addToCart"
        class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-indigo-700 transition">
        Añadir al carrito
    </button>

    @if ($showSuccess)
        <div class="mt-3 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ $message }}
        </div>
    @endif

    @if ($showError)
        <div class="mt-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $message }}
        </div>
    @endif
</div>

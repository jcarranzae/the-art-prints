<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_10_18_000004_create_product_variants_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: "Talla M - Rojo"
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Si varía del precio base
            $table->integer('stock')->default(0);
            
            // Atributos flexibles (JSON para variantes como color, talla, etc.)
            $table->json('attributes'); // {"size": "M", "color": "Red"}
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
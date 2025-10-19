<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_10_18_000008_create_coupons_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // Tipo de descuento
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // % o cantidad fija
            
            // Restricciones
            $table->decimal('min_purchase', 10, 2)->nullable(); // Compra mínima
            $table->integer('usage_limit')->nullable(); // Límite total de usos
            $table->integer('usage_per_user')->nullable(); // Límite por usuario
            $table->integer('used_count')->default(0);
            
            // Validez
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable(); // <- Cambiado: sin foreignId
            $table->timestamp('used_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
        Schema::dropIfExists('coupons');
    }
};
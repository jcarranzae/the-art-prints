<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_10_18_000002_create_products_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable(); // Precio antes de descuento
            $table->decimal('cost', 10, 2)->nullable(); // Costo del producto
            
            // Tipo de producto: digital, physical, service
            $table->enum('type', ['digital', 'physical', 'service'])->default('digital');
            
            // Para productos digitales
            $table->string('file_path')->nullable(); // Ruta del archivo descargable
            $table->integer('download_limit')->nullable(); // Límite de descargas
            
            // Para productos físicos
            $table->integer('stock')->default(0);
            $table->boolean('track_inventory')->default(false);
            $table->decimal('weight', 8, 2)->nullable(); // Para cálculo de envío
            $table->string('sku')->nullable()->unique();
            
            // General
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('sales_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
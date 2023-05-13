<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';

            $table->id();

            $table->foreignId('menu_item_id')->references('id')->on('menu_items')->onUpdate('restrict')->onDelete('restrict');
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('restrict')->onDelete('restrict');

            $table->unsignedDecimal('qty', $precision = 6, $scale = 3); // Decimal Because qty can be weight like meat, fish and so on.
            $table->unsignedDecimal('unit_price', $precision = 6, $scale = 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

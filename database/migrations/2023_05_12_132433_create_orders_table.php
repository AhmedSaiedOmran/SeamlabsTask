<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';

            $table->id();

            $table->enum('type', Order::getTypesOfOrder());

            $table->foreignId('dine_in_order_id')->nullable()->references('id')->on('dine_in_orders')
                    ->onUpdate('restrict')->onDelete('restrict');

            $table->foreignId('delivery_order_id')->nullable()->references('id')->on('delivery_orders')
                    ->onUpdate('restrict')->onDelete('restrict');


            $table->unsignedDecimal('total', $precision = 8, $scale = 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

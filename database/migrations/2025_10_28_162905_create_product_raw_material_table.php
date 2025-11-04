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
Schema::create('product_raw_material', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade'); // stays bigint unsigned
    $table->integer('raw_material_id'); // signed INT to match raw_materials.id
    $table->foreign('raw_material_id')
          ->references('id')
          ->on('raw_materials')
          ->onDelete('cascade');
    $table->decimal('quantity_required', 8, 2);
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_raw_material');
    }
};

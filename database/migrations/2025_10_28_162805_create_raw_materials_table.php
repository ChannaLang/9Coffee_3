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
Schema::create('raw_materials', function (Blueprint $table) {
    $table->integer('id')->primary(); // manual ID, signed INT
    $table->string('name')->unique();
    $table->decimal('quantity', 8, 2)->default(0);
    $table->string('unit')->default('unit');
    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};

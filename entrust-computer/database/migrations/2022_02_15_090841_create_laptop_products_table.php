<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laptop_products', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('description');
            $table->integer('price');
            $table->string('model_no');
            $table->string('product_code')->unique();
            $table->string('processor_manufacturer');
            $table->string('processor_info');
            $table->string('memory_capacity');
            $table->string('storage_type');
            $table->string('storage_capacity');
            $table->string('graphics_manufacturer');
            $table->string('graphics_capacity');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laptop_products');
    }
};

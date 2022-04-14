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
        Schema::create('laptop_product_ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('product_code');
            $table->integer('opening_stock')->nullable();
            $table->integer('closing_stock')->nullable();
            $table->string('description');
            $table->string('particular');
            $table->string('ref_no')->unique();
            $table->integer('sales')->nullable();
            $table->integer('supply')->nullable();
            $table->integer('balance')->default(0);
            $table->timestamps();

            $table->foreign('product_code')
                ->references('product_code')
                ->on('laptop_products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laptop_product_ledgers');
    }
};

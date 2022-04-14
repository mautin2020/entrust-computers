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
        Schema::create('laptop_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('product_name');
            $table->integer('quantity');
            $table->integer('cost_price');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('product_name')
                    ->references('product_name')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laptop_supplies');
    }
};

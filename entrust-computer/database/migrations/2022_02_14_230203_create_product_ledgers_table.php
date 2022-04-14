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
        Schema::create('product_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->date('date');
            $table->integer('opening_stock')->nullable();
            $table->integer('closing_stock')->nullable();
            $table->string('description');
            $table->string('particular');
            $table->string('ref_no')->unique();
            $table->integer('supply')->nullable();
            $table->integer('sales')->nullable();
            $table->integer('balance')->default(0);
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
        Schema::dropIfExists('product_ledgers');
    }
};

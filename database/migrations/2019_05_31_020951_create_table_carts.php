<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete(DB::raw("cascade"));
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete(DB::raw("cascade"));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}

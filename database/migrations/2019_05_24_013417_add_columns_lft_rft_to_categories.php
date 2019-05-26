<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsLftRftToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('lft')->after('parent_id')->nullable();
            $table->integer('rgt')->after('lft')->nullable();
            $table->integer('depth')->after('rgt')->nullable();
            $table->string('alias')->after('title')->unique()->nullable();
            $table->enum('active', [1, 0])->after('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'lft',
                'rgt',
                'depth',
                'alias',
                'active',
            ]);
        });
    }
}

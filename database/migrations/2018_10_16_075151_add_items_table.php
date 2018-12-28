<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_code', 255);
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->integer('manufacturer_id');
            $table->string('photos')->nullable();
            $table->float('price', 11, 2)->default(0);
            $table->integer('current_stock')->default(0);
            $table->integer('total_stock')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        //
    }
}

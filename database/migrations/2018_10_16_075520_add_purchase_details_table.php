<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id');
            $table->integer('product_id');
            $table->float('total_amount', 11, 2);
            $table->float('total_tax', 11, 2);
            $table->integer('total_quantity');
            $table->integer('customer_id');
            $table->integer('per_unit_id');
            $table->float('amount', 11, 2);
            $table->float('tax', 11, 2);
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

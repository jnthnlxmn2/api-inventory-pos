<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tracking_number', 255);
            $table->integer('total_quantity');
            $table->float('total_amount', 11, 2)->default(0);
            $table->float('tax', 11, 2)->default(0);
            $table->integer('tax_id');
            $table->integer('discount_id');
            $table->float('total_discounted_amount', 11, 2)->default(0);
            $table->float('amount_paid', 11, 2)->default(0);
            $table->float('change', 11, 2)->default(0);
            $table->string('status', 255)->default('paid');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->float('vat', 11, 2)->default(0);
            $table->float('vatable', 11, 2)->default(0);
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

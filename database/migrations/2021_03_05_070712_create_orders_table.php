<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('basket_id');
            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade');
            $table->string('transaction_value')->nullable()->unique();
            $table->boolean('is_paid')->default(0);
            $table->string('factor_number')->nullable()->unique();
            $table->decimal('discount_value', '20', '2')->nullable();
            $table->string('discount_code')->nullable();
            $table->float('discount_percent', 20, 2)->nullable();
            $table->decimal('final_paid_price')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}

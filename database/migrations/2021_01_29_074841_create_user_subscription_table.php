<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_info_id');
            $table->foreign('device_info_id')->references('id')->on('device_info')->onDelete('cascade');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('is_renewable')->default(1);
            $table->text('original_transaction_id')->nullable();
            $table->text('receipt_base64_data')->nullable();
            $table->tinyInteger('type');
            $table->string('status')->comment('started, renewed, canceled');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('user_subscription');
    }
}

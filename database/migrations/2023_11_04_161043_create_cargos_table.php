<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name');
            $table->string('sender_mobile');
            $table->string('receiver_name');
            $table->string('receiver_mobile');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('delivery_id')-> nullable();
            $table->text('origin_address');
            $table->text('destination_address');
            $table->double('origin_lat', 10,8);
            $table->double('origin_long', 11,8);
            $table->double('destination_lat', 10,8);
            $table->double('destination_long', 11,8);
            $table->integer('status')->default(0);
            $table->integer('tracking_code')->unique();

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
        Schema::dropIfExists('cargos');
    }
}

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
        Schema::create('daily_room_occupancy_rates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_id')
                ->index();
            $table->date('date')
                ->index();
            $table->float('rate');
            $table->integer('total_booked');
            $table->integer('total_blocked');
            $table->integer('total_capacity');
            $table->timestamps();

            $table->unique(['room_id', 'date']);

            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_room_occupancy_rates');
    }
};

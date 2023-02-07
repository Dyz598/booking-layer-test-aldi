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
        Schema::create('monthly_room_occupancy_rates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_id')
                ->index();
            $table->tinyInteger('month')
                ->index();
            $table->smallInteger('year')
                ->index();
            $table->float('rate');
            $table->integer('total_booked');
            $table->integer('total_blocked');
            $table->integer('total_capacity');
            $table->string('month_year', 20)
                ->index();
            $table->timestamps();

            $table->unique(['room_id', 'month', 'year']);

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
        Schema::dropIfExists('monthly_room_occupancy_rates');
    }
};

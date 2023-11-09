<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flight_data', function (Blueprint $table) {
            $table->id();

            $table->string('departure_city')->nullable();
            $table->string('arrival_city')->nullable();
            $table->dateTime('departure_time')->nullable();
            $table->dateTime('arrival_time')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_data');
    }
};

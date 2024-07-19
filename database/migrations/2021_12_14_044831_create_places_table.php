<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table -> integer('number') -> unsigned();
            $table -> string('type');
            $table -> boolean('select') ->default(false);
            $table -> float('posX') -> unsigned();
            $table -> float('posY') -> unsigned();
            $table -> integer('price') -> unsigned() -> default(0);
            $table -> integer('floor') -> unsigned() ->default(1);
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
        Schema::dropIfExists('places');
    }
}

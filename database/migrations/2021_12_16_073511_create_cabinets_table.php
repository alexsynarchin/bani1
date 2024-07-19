<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabinetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabinets', function (Blueprint $table) {
            $table->id();
            $table -> integer('number') -> unsigned();
            $table -> boolean('select') ->default(false);
            $table -> float('posX') -> unsigned();
            $table -> float('posY') -> unsigned();
            $table -> float('width') -> unsigned();
            $table -> float('height') -> unsigned();
            $table -> integer('price') -> unsigned() -> default(0);
            $table -> integer('floor') -> unsigned() ->default(2);
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
        Schema::dropIfExists('cabinets');
    }
}

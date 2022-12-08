<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudUbicacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_ubicacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('latitud', 11, 8);
            $table->decimal('longitud', 11, 8);
            $table->integer('activo')->default(1);

            $table->bigInteger('repartidor_id')->unsigned();
            $table->foreign('repartidor_id')->references('id')->on('repartidors');

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
        Schema::dropIfExists('solicitud_ubicacions');
    }
}

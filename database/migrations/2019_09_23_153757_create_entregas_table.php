<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('repartidor_id')->unsigned();
            //$table->bigInteger('sucursal_id')->unsigned();
            $table->bigInteger('pedido_id')->unsigned();
            $table->dateTime('fechahora')->nullable();
            $table->double('latitud_r')->nullable();
            $table->double('longitud_r')->nullable();
            $table->integer('estado')->default(0); //0: pendiente, 1:proceso , 2:entregado
            $table->text('descripcion')->nullable();
            $table->string('fotoentrega')->nullable();
            $table->integer('activo')->default(1);

            $table->foreign('repartidor_id')->references('id')->on('repartidors');
            //$table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
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
        Schema::dropIfExists('entregas');
    }
}

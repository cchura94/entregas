<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('direccion', 200);
            $table->string('latitud', 20)->nullable();
            $table->string('longitud', 20)->nullable();
            $table->string('telefono_c')->nullable();
            $table->string('nombre_c')->nullable();
            $table->integer('activo')->default(1);
            
            $table->bigInteger('tienda_id')->unsigned();
            $table->foreign('tienda_id')->references('id')->on('tiendas')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursals');
    }
}

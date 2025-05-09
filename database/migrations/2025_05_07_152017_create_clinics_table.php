<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinicas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('razao_social', 255);
            $table->string('nome_fantasia', 255);
            $table->string('cnpj', 14)->unique();
            $table->integer('regional');
            $table->date('data_inauguracao');
            $table->boolean('ativa')->default(true);
            $table->text('especialidades'); 
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
        //
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TAB_CONTRATOS', function (Blueprint $table) {
            $table->integer('co_unidade');
            $table->integer('nu_produto');
            $table->string('nu_contrato');
            $table->decimal('valor_base');
            $table->date('data_arquivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}

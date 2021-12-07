<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->default(1)->comment('1 - Contratista, 2 - Proveedor');
            $table->string('name')->nullable();
            $table->string('function')->nullable();
            /*$table->string('surnames')->nullable();
            $table->string('company');*/
            $table->string('bank');
            $table->string('clabe');
            $table->string('account');
            $table->string('bill')->default('0');
            $table->softDeletes();
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
        Schema::dropIfExists('providers');
    }
}

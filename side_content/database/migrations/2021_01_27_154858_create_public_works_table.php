<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('budget');
            /*$table->string('supervisor');*/
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->comment('1 - Activo, 2 - Inactivo');
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
        Schema::dropIfExists('public_works');
    }
}

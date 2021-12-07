<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('photography');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->date('birthdate');
            $table->string('cell_phone');
            $table->string('direction');
            $table->string('imss_number')->nullable();
            $table->boolean('imss')->default(1)->comment('0 - Inactivo, 1 - Activo');
            $table->string('curp');
            $table->string('rfc');
            /*$table->string('aptitudes');*/
            $table->string('stall');
            $table->string('salary_week');
            $table->date('registration_date');
            /*$table->string('phone');*/
            $table->string('status');
            $table->string('bank');
            $table->string('clabe');
            $table->string('account');
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
        Schema::dropIfExists('employees');
    }
}

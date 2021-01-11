<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsetResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aset_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->string('description');
            $table->dateTime('createdAt')->nullable();
            $table->integer('createdBy')->nullable();
            $table->dateTime('updatedAt')->nullable();
            $table->integer('updatedBy')->nullable();
            $table->dateTime('deletedAt')->nullable();
            $table->integer('deletedBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aset_resources');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id_reservation');
            $table->string('username');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('asset_id')
                ->nullable()
                ->constrained('asset')
                ->onUpdate('no action')
                ->onDelete('set null');
            $table->string('asset_name');
            $table->string('asset_description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('approval_status', ['not_yet_approved', 'already_approved', 'rejected'])
                ->default('not_yet_approved');
            $table->dateTime('approval_date')
                ->nullable();
            $table->uuid('user_id_updated')->nullable();
            $table->uuid('user_id_deleted')->nullable();
            $table->softDeletes();
            $table->index(['title', 'asset_name' , 'username']);
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
        Schema::dropIfExists('reservations');
    }
}

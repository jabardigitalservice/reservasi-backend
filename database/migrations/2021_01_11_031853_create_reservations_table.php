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
            $table->string('reservation_title');
            $table->string('reservation_description')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('asset_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('no action')
                ->onDelete('set null');
            $table->string('asset_name');
            $table->string('asset_description')->nullable();
            $table->dateTime('reservation_start');
            $table->dateTime('reservation_end');
            $table->enum('approval_status', ['not_yet_approved', 'already_approved', 'rejected'])
                ->default('not_yet_approved');
            $table->dateTime('approval_date')
                ->nullable();
            $table->uuid('user_id_updated');
            $table->uuid('user_id_deleted');
            $table->softDeletes();
            $table->index(['reservation_title', 'asset_name' , 'username']);
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

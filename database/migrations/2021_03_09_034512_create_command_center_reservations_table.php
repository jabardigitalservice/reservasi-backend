<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandCenterReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_center_reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id_reservation');
            $table->string('reservation_code')->index();
            $table->string('name')->index();
            $table->string('nik')->unique();
            $table->string('organization_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->index();
            $table->string('email')->unique()->index();
            $table->string('purpose');
            $table->integer('visitors');
            $table->dateTime('reservation_date')->index();
            $table->enum('shift', ['1', '2']);
            $table->enum('approval_status', ['not_yet_approved', 'already_approved', 'rejected'])
                ->default('not_yet_approved');
            $table->dateTime('approval_date')
                ->nullable();
            $table->uuid('user_id_updated')->nullable();
            $table->uuid('user_id_deleted')->nullable();
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
        Schema::dropIfExists('command_center_reservations');
    }
}

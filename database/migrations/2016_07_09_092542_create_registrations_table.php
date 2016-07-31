<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('registration_type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('fined')->default(false);
            $table->boolean('activated')->default(false);
            $table->string('activation_code', 10)->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('registration_type_id')->references('id')->on('registration_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('registrations');
    }
}

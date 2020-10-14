<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('control_number')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('area_id');
            $table->date('birthdate');
            $table->longText('address')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('in_residences')->nullable();
            $table->boolean('in_social_service')->nullable();
            $table->string('phone');
            $table->boolean('visible_mail');
            $table->boolean('visible_phone');
            $table->integer('user_type');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

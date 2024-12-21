<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_catalogue_id')->default(2);
            $table->string('name', 50);
            $table->string('fullname', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('province_id', 10)->nullable();
            $table->string('district_id', 10)->nullable();
            $table->string('ward_id', 10)->nullable();
            $table->dateTime('birthday')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('publish')->default(0);
            $table->rememberToken()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};

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
        Schema::create('userCatalogue', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('image')->nullable();
            $table->tinyInteger('publish')->default(0);
            $table->text('description');
            $table->text('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userCatalogue');
    }
};

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
        Schema::table('user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_catalogue_id')->default(2)->change();
            $table->foreign('user_catalogue_id')
                ->references('id')
                ->on('userCatalogue')
                ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign(['user_catalogue_id']);
        });
    }
};

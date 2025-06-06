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
       Schema::create('products', function (Blueprint $table) {
$table->id();
$table->integer('post_catalogue_id')->default(0);
$table->string('image')->nullable();
$table->string('icon')->nullable();
$table->text('album')->nullable();
$table->tinyInteger('publish')->default(1);
$table->integer('order')->default(0);
$table->tinyInteger('follow')->default(0);
$table->unsignedBigInteger('user_id');
$table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
$table->text('deleted_at')->nullable();
$table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

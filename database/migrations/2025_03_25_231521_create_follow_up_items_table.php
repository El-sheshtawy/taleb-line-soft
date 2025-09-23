<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_up_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follow_up_id')->constrained()->onDelete('cascade');
            $table->string('letter', 5); // Example: غ
            $table->string('meaning', 255)->nullable();
            $table->string('background_color', 50)->nullable();
            $table->string('text_color', 50)->nullable();
            $table->boolean('is_absent')->default(false);  
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
        Schema::dropIfExists('follow_up_items');
    }
};

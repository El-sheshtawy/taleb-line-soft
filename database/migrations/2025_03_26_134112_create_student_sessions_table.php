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
        Schema::create('student_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('session_number');  // 1 to 7
            $table->text('teacher_note')->nullable();  
            
            $table->foreignId('student_day_id')->constrained('student_days')->onDelete('cascade');
            $table->foreignId('follow_up_item_id')->nullable()->constrained('follow_up_items')->onDelete('SET NULL');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('student_sessions');
    }
};

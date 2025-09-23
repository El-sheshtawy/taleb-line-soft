<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('passport_id', 12)->unique();
            $table->string('phone_number', 8)->nullable();
            $table->string('subject');
            
            $table->boolean('head_of_department')->default(false);
            $table->boolean('supervisor')->default(false);
            
            $table->foreignId('nationality_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained('school_accounts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};

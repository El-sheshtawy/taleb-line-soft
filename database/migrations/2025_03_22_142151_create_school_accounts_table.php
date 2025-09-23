<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('school_logo_url')->nullable();
            $table->string('school_banner_url')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('subscription_state', ['active', 'inactive'])->default('inactive');
            $table->string('edu_region')->nullable();
            $table->string('teachers_default_password')->nullable();
            $table->string('students_default_password')->nullable();
            $table->integer('absence_count')->default(2);
            
            $table->foreignId('follow_up_id')->nullable()->constrained('follow_ups')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_accounts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For SQLite, we need to recreate the table with new enum values
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'student', 'teacher', 'father', 'school', 'مراقب', 'مشرف'])->default('student')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'student', 'teacher', 'father', 'school'])->after('id');
        });
    }
};

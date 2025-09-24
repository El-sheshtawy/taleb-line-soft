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
        Schema::table('school_accounts', function (Blueprint $table) {
            $table->string('viewer_name')->nullable();
            $table->string('viewer_password')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_accounts', function (Blueprint $table) {
            $table->dropColumn(['viewer_name', 'viewer_password', 'supervisor_name', 'supervisor_password']);
        });
    }
};

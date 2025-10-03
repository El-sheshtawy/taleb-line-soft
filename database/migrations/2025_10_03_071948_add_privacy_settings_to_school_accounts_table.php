<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('school_accounts', function (Blueprint $table) {
            $table->boolean('hide_passport_id')->default(false);
            $table->boolean('hide_phone1')->default(false);
            $table->boolean('hide_phone2')->default(false);
        });
    }

    public function down()
    {
        Schema::table('school_accounts', function (Blueprint $table) {
            $table->dropColumn(['hide_passport_id', 'hide_phone1', 'hide_phone2']);
        });
    }
};
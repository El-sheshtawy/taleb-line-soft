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
        // For SQLite, we'll just add a comment since it doesn't support ENUM modifications
        // The new roles will work as the column is already a string type
        // No actual schema change needed for SQLite
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No changes needed for rollback in SQLite
    }
};

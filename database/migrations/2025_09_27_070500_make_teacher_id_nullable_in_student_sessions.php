<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For SQLite, we need to recreate the table
        DB::statement('PRAGMA foreign_keys=off');
        DB::statement('ALTER TABLE student_sessions RENAME TO student_sessions_old');
        
        DB::statement('
            CREATE TABLE student_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                teacher_id BIGINT UNSIGNED NULL,
                session_number INTEGER NOT NULL,
                follow_up_item_id BIGINT UNSIGNED NULL,
                student_day_id BIGINT UNSIGNED NOT NULL,
                teacher_note TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ');
        
        DB::statement('INSERT INTO student_sessions SELECT * FROM student_sessions_old');
        DB::statement('DROP TABLE student_sessions_old');
        DB::statement('PRAGMA foreign_keys=on');
    }

    public function down()
    {
        // Reverse operation
        DB::statement('PRAGMA foreign_keys=off');
        DB::statement('ALTER TABLE student_sessions RENAME TO student_sessions_old');
        
        DB::statement('
            CREATE TABLE student_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                teacher_id BIGINT UNSIGNED NOT NULL,
                session_number INTEGER NOT NULL,
                follow_up_item_id BIGINT UNSIGNED NULL,
                student_day_id BIGINT UNSIGNED NOT NULL,
                teacher_note TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ');
        
        DB::statement('INSERT INTO student_sessions SELECT * FROM student_sessions_old WHERE teacher_id IS NOT NULL');
        DB::statement('DROP TABLE student_sessions_old');
        DB::statement('PRAGMA foreign_keys=on');
    }
};
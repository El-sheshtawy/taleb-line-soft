<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\SchoolAccount;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Level;
use App\Models\Grade;
use App\Models\ClassRoom;
use App\Models\FollowUp;
use App\Models\FollowUpItem;
use App\Models\Nationality;
use App\Models\AcademicYear;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create basic data first
        $this->createBasicData();
        
        // Create test users for each role
        $this->createTestUsers();
        
        // Create test school with resources
        $this->createTestSchool();
    }

    private function createBasicData()
    {
        // Create Level
        $level = Level::firstOrCreate(['name' => 'الابتدائية']);
        
        // Create Grade
        $grade = Grade::firstOrCreate([
            'name' => 'الصف الأول',
            'level_id' => $level->id
        ]);

        // Create Nationality
        Nationality::firstOrCreate(['name' => 'كويتي']);
        Nationality::firstOrCreate(['name' => 'مصري']);

        // Create Academic Year
        AcademicYear::firstOrCreate([
            'name' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-06-30'
        ]);

        // Create Follow Up System
        $followUp = FollowUp::firstOrCreate([
            'name' => 'نظام المتابعة التجريبي',
            'notes' => 'نظام متابعة للاختبار'
        ]);

        // Create Follow Up Items
        $items = [
            ['letter' => 'غ', 'meaning' => 'غائب', 'background_color' => '#dc3545', 'text_color' => '#ffffff', 'is_absent' => true],
            ['letter' => 'ت', 'meaning' => 'تأخير', 'background_color' => '#ffc107', 'text_color' => '#000000', 'is_absent' => false],
            ['letter' => 'م', 'meaning' => 'مشاغب', 'background_color' => '#fd7e14', 'text_color' => '#ffffff', 'is_absent' => false],
        ];

        foreach ($items as $item) {
            FollowUpItem::firstOrCreate([
                'follow_up_id' => $followUp->id,
                'letter' => $item['letter']
            ], $item);
        }
    }

    private function createTestUsers()
    {
        $nationality = Nationality::first();

        // 1. Admin User
        $adminUser = User::firstOrCreate([
            'username' => 'admin'
        ], [
            'user_type' => 'admin',
            'password' => Hash::make('123456'),
            'defualt_password' => Hash::make('123456')
        ]);

        Admin::firstOrCreate([
            'user_id' => $adminUser->id
        ], [
            'name' => 'مدير النظام',
            'username' => 'admin',
            'password' => Hash::make('123456')
        ]);

        // 2. School User
        $schoolUser = User::firstOrCreate([
            'username' => 'school_test'
        ], [
            'user_type' => 'school',
            'password' => Hash::make('123456'),
            'defualt_password' => Hash::make('123456')
        ]);

        $followUp = FollowUp::first();
        $level = Level::first();

        SchoolAccount::firstOrCreate([
            'user_id' => $schoolUser->id
        ], [
            'school_name' => 'مدرسة الاختبار الابتدائية',
            'username' => 'school_test',
            'password' => Hash::make('123456'),
            'subscription_state' => 'active',
            'edu_region' => 'منطقة تعليمية تجريبية',
            'teachers_default_password' => '123456',
            'students_default_password' => '123456',
            'absence_count' => 3,
            'follow_up_id' => $followUp->id,
            'level_id' => $level->id
        ]);

        // 3. Teacher User
        $teacherUser = User::firstOrCreate([
            'username' => 'teacher_test'
        ], [
            'user_type' => 'teacher',
            'password' => Hash::make('123456'),
            'defualt_password' => Hash::make('123456')
        ]);

        $school = SchoolAccount::first();

        Teacher::firstOrCreate([
            'user_id' => $teacherUser->id
        ], [
            'name' => 'أحمد محمد المعلم',
            'phone_number' => '12345678',
            'subject' => 'الرياضيات',
            'passport_id' => '123456789012',
            'head_of_department' => false,
            'supervisor' => false,
            'school_id' => $school->id,
            'nationality_id' => $nationality->id
        ]);

        // 4. Viewer User (مراقب)
        $viewerUser = User::firstOrCreate([
            'username' => 'viewer_test'
        ], [
            'user_type' => 'مراقب',
            'password' => Hash::make('123456'),
            'defualt_password' => Hash::make('123456')
        ]);

        Teacher::firstOrCreate([
            'user_id' => $viewerUser->id
        ], [
            'name' => 'سارة أحمد المراقبة',
            'phone_number' => '87654321',
            'subject' => 'عام',
            'passport_id' => '210987654321',
            'head_of_department' => false,
            'supervisor' => false,
            'school_id' => $school->id,
            'nationality_id' => $nationality->id
        ]);

        // 5. Supervisor User (مشرف)
        $supervisorUser = User::firstOrCreate([
            'username' => 'supervisor_test'
        ], [
            'user_type' => 'مشرف',
            'password' => Hash::make('123456'),
            'defualt_password' => Hash::make('123456')
        ]);

        Teacher::firstOrCreate([
            'user_id' => $supervisorUser->id
        ], [
            'name' => 'محمد علي المشرف',
            'phone_number' => '11223344',
            'subject' => 'العلوم',
            'passport_id' => '112233445566',
            'head_of_department' => false,
            'supervisor' => false,
            'school_id' => $school->id,
            'nationality_id' => $nationality->id
        ]);
    }

    private function createTestSchool()
    {
        $school = SchoolAccount::first();
        $grade = Grade::first();
        $academicYear = AcademicYear::first();
        $nationality = Nationality::first();

        // Create Classes
        $class1 = ClassRoom::firstOrCreate([
            'name' => 'الفصل الأول أ',
            'grade_id' => $grade->id,
            'school_id' => $school->id
        ], [
            'meeting_room_link' => 'https://meet.google.com/test-room-1',
            'academic_year_id' => $academicYear->id
        ]);

        $class2 = ClassRoom::firstOrCreate([
            'name' => 'الفصل الأول ب',
            'grade_id' => $grade->id,
            'school_id' => $school->id
        ], [
            'meeting_room_link' => 'https://meet.google.com/test-room-2',
            'academic_year_id' => $academicYear->id
        ]);

        // Create Students
        $students = [
            ['name' => 'أحمد محمد علي', 'passport_id' => '111111111111'],
            ['name' => 'فاطمة أحمد سالم', 'passport_id' => '222222222222'],
            ['name' => 'محمد سعد الدين', 'passport_id' => '333333333333'],
            ['name' => 'نور الهدى محمد', 'passport_id' => '444444444444'],
            ['name' => 'عبدالله أحمد', 'passport_id' => '555555555555'],
        ];

        foreach ($students as $index => $studentData) {
            $studentUser = User::firstOrCreate([
                'username' => $studentData['passport_id']
            ], [
                'user_type' => 'student',
                'password' => Hash::make('123456'),
                'defualt_password' => Hash::make('123456')
            ]);

            Student::firstOrCreate([
                'user_id' => $studentUser->id
            ], [
                'name' => $studentData['name'],
                'passport_id' => $studentData['passport_id'],
                'phone_number' => '9999999' . ($index + 1),
                'phone_number_2' => '8888888' . ($index + 1),
                'class_id' => $index < 3 ? $class1->id : $class2->id,
                'grade_id' => $grade->id,
                'school_id' => $school->id,
                'nationality_id' => $nationality->id
            ]);
        }

        // Create Additional Teachers
        $teachers = [
            ['name' => 'خالد أحمد', 'subject' => 'اللغة العربية', 'passport_id' => '777777777777'],
            ['name' => 'منى سالم', 'subject' => 'اللغة الإنجليزية', 'passport_id' => '888888888888'],
        ];

        foreach ($teachers as $teacherData) {
            $teacherUser = User::firstOrCreate([
                'username' => $teacherData['passport_id']
            ], [
                'user_type' => 'teacher',
                'password' => Hash::make('123456'),
                'defualt_password' => Hash::make('123456')
            ]);

            Teacher::firstOrCreate([
                'user_id' => $teacherUser->id
            ], [
                'name' => $teacherData['name'],
                'phone_number' => '55555555',
                'subject' => $teacherData['subject'],
                'passport_id' => $teacherData['passport_id'],
                'head_of_department' => false,
                'supervisor' => false,
                'school_id' => $school->id,
                'nationality_id' => $nationality->id
            ]);
        }
    }
}
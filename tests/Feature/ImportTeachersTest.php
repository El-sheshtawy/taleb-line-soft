<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\SchoolAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class ImportTeachersTest extends TestCase
{
    /** @test */
    public function it_can_import_teachers_from_excel()
    {
        // Use database transactions to keep DB clean
        $this->withoutExceptionHandling();

        // Fake storage for testing file uploads
        Storage::fake('local');

        // Create a school account
        $school = SchoolAccount::factory()->create();

        // Generate fake teacher data
        $teachersData = [];
        for ($i = 0; $i < 50; $i++) {
            $teachersData[] = [
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'phone_number' => fake()->phoneNumber,
                'subject' => fake()->word,
                'nationality_id' => 1,
                'password' => '123456',
            ];
        }

        // Create an Excel file with the teachers' data
        $fileName = 'temp_teachers.xlsx';
        $filePath = storage_path("app/{$fileName}");

        // Use OpenSpout Writer to create Excel
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        // Add header row
        $writer->addRow(WriterEntityFactory::createRowFromArray(array_keys($teachersData[0])));

        // Add teacher data rows
        foreach ($teachersData as $teacher) {
            $writer->addRow(WriterEntityFactory::createRowFromArray($teacher));
        }

        $writer->close();

        // Simulate a file upload
        $file = new UploadedFile($filePath, $fileName, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Send request to import endpoint
        $response = $this->post(route('teachers.import'), [
            'file' => $file,
        ]);

        // Assert success
        $response->assertRedirect(route('school.index'));
        $this->assertDatabaseCount('teachers', 50);
    }
}

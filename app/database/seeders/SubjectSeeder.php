<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::create([
            'subject' => 'Matematika',
        ]);

        Subject::create([
            'subject' => 'IPA',
        ]);

        Subject::create([
            'subject' => 'IPS',
        ]);

        Subject::create([
            'subject' => 'Pendidikan Agama Islam',
        ]);

        Subject::create([
            'subject' => 'Penjaskes',
        ]);

        Subject::create([
            'subject' => 'PKN',
        ]);

        Subject::create([
            'subject' => 'Bahasa Indonesia',
        ]);

        Subject::create([
            'subject' => 'Bahasa Inggris',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\AD;
use App\Models\ADType;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ADSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->allRecords();

        foreach ($data['students'] as $student) {
            foreach ($data['adTypes'] as $adType) {
                foreach ($data['periods'] as $period) {
                    $record = AD::where('a_d_type_id', $adType->id)
                        ->where('student_id', $student->id)
                        ->where('period_id', $period->id);

                    if ($record->exists()) {
                        continue;
                    }

                    AD::create([
                        'period_id' => $period->id,
                        'student_id' => $student->id,
                        'value' => mt_rand(1, 5),
                        'a_d_type_id' => $adType->id,
                    ]);
                }
            }
        }
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();
        $adType = ADType::first();

        //if any of the required values are empty seed their tables
        if (! $period) {
            Artisan::call('db:seed', ['--class' => 'PeriodSeeder']);
        }
        if (! $student) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }
        if (! $adType) {
            Artisan::call('db:seed', ['--class' => 'ADTypeSeeder']);
        }

        $periods = Period::all();
        $students = Student::where('is_active', true)->get();
        $adTypes = ADType::all();

        return [
            'periods' => $periods,
            'students' => $students,
            'adTypes' => $adTypes,
        ];
    }
}

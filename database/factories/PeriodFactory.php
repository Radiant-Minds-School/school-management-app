<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Period::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $academicSession = AcademicSession::factory()->create();
        $term = Term::factory()->create();

        $slug = Str::of("{$academicSession->name} {$term->slug}")->slug('-');

        $startDate = Carbon::createFromFormat('Y-m-d', $academicSession->start_date->toDateString());
        $startDate = $startDate->addDays(mt_rand(1, 20));

        do {
            $rank = mt_rand(1, 100);
            $period = Period::where('rank', $rank)->first();
        } while ($period != null);

        return [
            'academic_session_id' => $academicSession->id,
            'term_id' => $term->id,
            'slug' => $slug,
            'start_date' => $startDate,
            'end_date' => $academicSession->end_date,
            'active' => null,
            'rank' => $rank,
            'no_times_school_opened' => mt_rand(100, 120),
        ];
    }
}

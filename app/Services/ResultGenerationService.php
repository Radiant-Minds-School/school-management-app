<?php

namespace App\Services;

use App\Models\ADType;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherRemark;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for result generation
 */
class ResultGenerationService
{

    public function __construct(private Student $student, private Period $period)
    {
    }

    /**
     * generate Report
     */
    public function generateReport(): array
    {
        $classroom = $this->getClassroom();

        $subjects = $this->getPeriodSubjects($classroom);
        $attendance = $this->student->attendances()->where('period_id', $this->period->id)->first();

        /**
         * Check if the class has subjects
         * Classroom's academic session subjects are needed to generate report
         */
        if (count($subjects) < 1) {
            throw new Exception("Student's class does not have subjects");
        }

        $results = $this->getSubjectResults($subjects);

        $teacherRemark = TeacherRemark::where('student_id', $this->student->id)->where('period_id', $this->period->id)->first();

        $totalObtained = $results->sum('total');
        $totalObtainable = $subjects->count() * 100;

        $scores = $this->getScores($results);
        $minScores = ($scores->map(fn($score) => $score['min']));
        $maxScores = ($scores->map(fn($score) => $score['max']));
        $avgScores = ($scores->map(fn($score) => $score['avg']));
        return [
            'student' => $this->student,
            'totalObtained' => $totalObtained,
            'totalObtainable' => $totalObtainable,
            'percentage' => $totalObtained / $totalObtainable * 100,
            'results' => $results,
            'maxScores' => $maxScores,
            'averageScores' => $avgScores,
            'minScores' => $minScores,
            'age' => $this->student->age(),
            'pds' => $this->getPds(),
            'pdTypes' => PDType::all(),
            'ads' => $this->getAds(),
            'adTypes' => ADType::all(),
            'period' => $this->period,
            'nextTermBegins' => $this->getNextTermDetails()['nextTermBegins'],
            // 'nextTermFee' => $nextTermDetails['nextTermFee'],
            'teacherRemark' => $teacherRemark,
            'classroom' => $classroom,
            'no_of_times_present' => $attendance,
        ];
    }

    private function getScores(Collection $results): Collection
    {
        // Get all the min, max, and average scores for the period and classroom at once
        $scores = Result::where('period_id', $this->period->id)
            ->whereIn('subject_id', $results->pluck('subject_id')->unique())
            ->where('classroom_id', $results->first()->classroom_id)
            ->groupBy('subject_id')
            ->selectRaw('subject_id, MIN(total) as min_total, MAX(total) as max_total, AVG(total) as avg_total')
            ->get()
            ->keyBy('subject_id');

        return $results->mapWithKeys(function (Result|null $result) use ($scores) {
            $key = $result?->subject->name;
            $score = $scores[$result?->subject_id] ?? null;

            return [
                $key => [
                    'min' => $score?->min_total,
                    'max' => $score?->max_total,
                    'avg' => $score?->avg_total,
                ]
            ];
        });
    }

    /**
     * Get Psychomotor domains for a given period
     */
    private function getPds(): array
    {
        // get pds for period with PDType
        $pds = $this->student->pds()->with('pdType')->where('period_id', $this->period->id)->get();

        // use mapWithKeys to create an associative array with pd type names as keys and values as values
        $pds = $pds->mapWithKeys(function ($pd) {
            return [$pd->pdType->name => $pd->value];
        });

        return $pds->toArray();
    }

    /**
     * Get Affective domains for given period
     *
     * @param  Period  $period
     * @return array
     */
    private function getAds(): array
    {
        // get ads for period with ADType
        $ads = $this->student->ads()->with('adType')->where('period_id', $this->period->id)->get();

        // use mapWithKeys to create an associative array with ad type names as keys and values as values
        $ads = $ads->mapWithKeys(function ($ad) {
            return [$ad->adType->name => $ad->value];
        });

        return $ads->toArray();
    }

    /**
     * Get next term details
     */
    private function getNextTermDetails(): array
    {
        $nextPeriod = Period::where('rank', $this->period->rank + 1)->first();

        if (is_null($nextPeriod)) {
            $nextTermBegins = null;
            // $nextTermFee = null;
        } else {
            $nextTermBegins = $nextPeriod->start_date;
            // $nextTermFee = Fee::where('classroom_id', $this->student->classroom->id)
            //     ->where('period_id', $nextPeriod->id)->first();

            // //check if next term fee is available
            // if (is_null($nextTermFee)) {
            //     $nextTermFee = null;
            // } else {
            //     $nextTermFee = number_format($nextTermFee->amount);
            // }
        }

        return [
            'nextTermBegins' => $nextTermBegins,
            // 'nextTermFee' => $nextTermFee
        ];
    }

    /**
     * Get the subjects for the student's class in the selected period's Academic Session
     */
    private function getPeriodSubjects(Classroom $classroom): Collection
    {
        return Subject::whereIn('id', function ($query) use ($classroom) {
            $query->select('subject_id')
                ->from('classroom_subject')
                ->where('academic_session_id', $this->period->academicSession->id)
                ->where('classroom_id', $classroom->id);
        })->get();
    }

    /**
     * Get the classroom for the student in the selected period
     */
    private function getClassroom(): Classroom
    {
        return $this->student->results()->where('period_id', $this->period->id)->firstOrFail()->classroom;
    }

    private function getSubjectResults(Collection $subjects): Collection
    {
        // Get all results for the student, period, and subjects at once
        $results = Result::with('subject')
            ->where('student_id', $this->student->id)
            ->where('period_id', $this->period->id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->keyBy('subject_id');

        return $subjects->mapWithKeys(function (Subject $subject) use ($results) {
            return [$subject->name => $results[$subject->id] ?? null];
        });
    }
}
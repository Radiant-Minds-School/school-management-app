<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Branch;
use App\Models\BranchClassroom;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Validate request
     *
     * @param  mixed $request
     * @return array
     */
    private function classroomValidation($request)
    {

        $messages = [
            'name.unique' => 'Classroom Exists',
        ];

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'unique:classrooms'],
        ], $messages);

        return $validatedData;
    }

    /**
     * Show classrooms page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $classrooms = Classroom::all()->sortBy('rank');
        return view('classroom.index', compact('classrooms'));
    }

    /**
     * Store classroom
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $maxRank = Classroom::max('rank');
        $rank = ['rank' => $maxRank + 1];
        $validatedData = $this->classroomValidation($request);
        $slug = ['slug' => Str::of($validatedData['name'])->slug('-')];
        $data = $rank + $slug + $validatedData;
        Classroom::create($data);
        return back()->with('success', 'Classroom Created!');
    }

    /**
     * Show edit classroom page
     *
     * @param  Classroom $classroom
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Classroom $classroom)
    {
        return view('classroom.edit', compact('classroom'));
    }

    /**
     * Update classroom
     *
     * @param  Classroom $classroom
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Classroom $classroom, Request $request)
    {
        $classrooms = Classroom::all();
        $maxRank = $classrooms->max('rank');
        $currentRank = $classroom->rank;

        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('classrooms')->ignore($classroom)],
            'rank' => ['required', 'numeric', 'min:1', 'max:' . $maxRank],
        ]);

        /**
         * get row where rank is equal to the posted rank and if it exists
         * set the rank of the row to 0, then update the classroom that need to be updated
         * to avoid unique constraint error. Set the row whose rank was set to 0
         * to the previous rank of the updated classroom model.
         */
        $rank = $validatedData['rank'];
        $row = Classroom::where('rank', $rank)->first();
        $slug = ['slug' => Str::of($validatedData['name'])->slug('-')];

        //if row exists
        if (!is_null($row)) {
            $row->rank = 0;
            $row->save();
            $classroom->update($validatedData + $slug);
            $row->rank = $currentRank;
            $row->save();
        } else {
            $classroom->update($validatedData + $slug);
        }

        return redirect(route('classroom.index'))->with('success', 'Classroom Updated!');
    }

    /**
     * Show classroom
     *
     * @param  Classroom $classroom
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function show(Classroom $classroom)
    {
        $students = Student::with('guardian')->whereNull('graduated_at')->where('is_active', true)->where('classroom_id', $classroom->id)->get();

        $academicSessions = AcademicSession::all();
        $terms = Term::all();
        $activePeriod = Period::activePeriod();

        if (is_null($activePeriod)) {
            return back()->with('error', 'Current Academic session is not set!');
        } else {
            $currentAcademicSession = $activePeriod->academicSession;
        }

        $subjects = $classroom->subjects()->where('academic_session_id', $currentAcademicSession->id)->get();
        return view('classroom.show', compact('students', 'classroom', 'academicSessions', 'terms', 'subjects'));
    }

    /**
     * Delete classroom
     *
     * @param  Classroom $classroom
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Classroom can not be deleted because some resources are dependent on it!');
            }
        }

        /**
         * update the rank of the other classes
         *
         * get all the classes sorted by their current rank
         * and then loop through them to update their ranks
         * while incrementing the rank
         */
        $classrooms = Classroom::all()->sortBy('rank');
        $rank = 1;
        foreach ($classrooms as $classroom) {
            $classroom->update(['rank' => $rank]);
            $rank++;
        }

        return back()->with('success', 'Classroom Deleted!');
    }

    /**
     * Show set classroom subjects view
     *
     * @param  Classroom $classroom
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function setSubjects(Classroom $classroom)
    {
        $subjects = Subject::all();
        $relations = [];

        //NOTE: subjects can only be set for the current academic session
        if (Period::activePeriodIsNotSet()) {
            return back()->with('error', 'Academic Session is not set');
        }

        $currentAcademicSession = Period::activePeriod()->academicSession;

        //loop subjects and get the ones that are related to the classroom
        foreach ($subjects as $subject) {
            $relation = $subject->classrooms()->where('classroom_id', $classroom->id)->where('academic_session_id', $currentAcademicSession->id)->exists();
            $relations = array_merge($relations, [$subject->name => $relation]);
        }

        //set array as collection for it to be showable in the view
        $relations = collect($relations);

        return view('subject.set', compact('relations', 'classroom'));
    }

    /**
     * Update classroom subjects
     *
     * @param  Classroom $classroom
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSubjects(Classroom $classroom, Request $request)
    {
        if (Period::activePeriodIsNotSet()) {
            return back()->with('error', 'Active Period is not set!');
        }

        $currentAcademicSession = Period::activePeriod()->academicSession;

        //detach all subjects from classroom when no subject is provided
        if (!$request->has('subjects')) {
            $classroom->subjects()->wherePivot('academic_session_id', '=', $currentAcademicSession->id)->sync([]);
            return back()->with('success', 'Subjects set successfully');
        }

        $subjects = $request->subjects;
        $subjectIds = [];

        foreach ($subjects as $subject) {
            $subjectId = Subject::where('name', $subject)->first()->id;
            array_push($subjectIds, $subjectId);
        }

        //insert all subjectIds to the related class on the pivot table
        $classroom->subjects()->wherePivot('academic_session_id', '=', $currentAcademicSession->id)->syncWithPivotValues($subjectIds, ['academic_session_id' => $currentAcademicSession->id]);

        return back()->with('success', 'Subjects set successfully');
    }

    /**
     * Show promoteOrDemoteStudents view
     *
     * @param  Classroom $classroom
     * @return \Illuminate\Contracts\View\View
     */
    public function promoteOrDemoteStudents(Classroom $classroom)
    {
        $students = $classroom->getActiveStudents();

        return view('student.promote-demote', compact('students', 'classroom'));
    }

    /**
     * Promote multiple Students from a classroom
     *
     * @param  Request $request
     * @param  Classroom $classroom
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteStudents(Request $request, Classroom $classroom)
    {

        // if no student is selected
        if (!$request->has('students')) {
            return back()->with('error', 'No students selected');
        }

        $studentIds = $request->students;

        $classRank = $classroom->rank;
        $highestClassRank = Classroom::max('rank');

        // If students are not in the highest class promote them
        if ($classRank !== $highestClassRank) {
            $newClassRank = $classRank + 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;

            Student::find($studentIds)->map(function ($student) use ($newClassId) {
                $student->update(['classroom_id' => $newClassId]);
            });

            return back()->with('success', 'Students Promoted!');
        }

        return back()->with('error', 'Students is in the Maximum class possible');
    }

    /**
     * Demote multiple Students from a classroom
     *
     * @param  Request $request
     * @param  Classroom $classroom
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demoteStudents(Request $request, Classroom $classroom)
    {
        // if no student is selected
        if (!$request->has('students')) {
            return back()->with('error', 'No students selected');
        }

        $studentIds = $request->students;

        $classRank = $classroom->rank;
        $lowestClassRank = Classroom::min('rank');

        // If students are not in the lowest class demote them
        if ($classRank !== $lowestClassRank) {
            $newClassRank = $classRank - 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;

            Student::find($studentIds)->map(function ($student) use ($newClassId) {
                $student->update(['classroom_id' => $newClassId]);
            });

            return back()->with('success', 'Students Demoted!');
        }

        return back()->with('error', 'Student is in the Minimum class possible');
    }

    /**
     * Show a classroom branch
     *
     * @param  Classroom $classroom
     * @param  Branch $branch
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showBranch(Classroom $classroom, Branch $branch)
    {
        $branchClassroom = BranchClassroom::where('classroom_id', $classroom->id)
            ->where('branch_id', $branch->id)->first();

        return view('classroom.branch', compact('branch', 'classroom', 'branchClassroom'));
    }

    /**
     * Update Branches
     *
     * @param  Classroom $classroom
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBranches(Classroom $classroom, Request $request)
    {
        $branches = $request->branches;

        foreach ($branches as $branch) {

            // Validate if all the selected branches exist
            if (!Branch::where('name', $branch)->exists()) {
                return back()->with('error', "Branch $branch does not exist");
            }
        }

        $classroomBranches = $classroom->branches->pluck('name');
        $branches = collect($branches);

        $newBranches = $branches->diff($classroomBranches);
        $removedBranches = $classroomBranches->diff($branches);

        $removedBranches->map(function ($branch) use ($classroom) {

            $branch = Branch::where('name', $branch)->first();
            $branchClassroom = BranchClassroom::where('branch_id', $branch->id)->where('classroom_id', $classroom->id)->first();

            // Check if the branch has students
            if ($branchClassroom->students->count() > 1) {
                return back()->with('error', "Cannot remove $classroom->name $branch->name because it has students");
            }

            // Remove the branch from the classroom
            $classroom->branches()->detach($branch->id);
        });

        $newBranches->map(function ($branch) use ($classroom) {
            $branch = Branch::where('name', $branch)->first();

            $classroom->branches()->attach($branch->id);
        });

        return back()->with('success', 'Branches Updated!');
    }
}

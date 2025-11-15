<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AcademicSessionController extends Controller
{
    use ValidationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $academicSessions = AcademicSession::all();

        // Log activity
        \activity()
            ->causedBy(auth()->user())
            ->log("Requested Academic Sessions view");

        return view("academic-session.index", compact("academicSessions"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $messages = [
            "name.required" => "This field is required",
            "name.unique" => "Record exists",
            "name.regex" => "Academic session format is invalid",
        ];

        $data = $request->validate(
            [
                "name" => [
                    "required",
                    "string",
                    "unique:academic_sessions",
                    'regex:/^\d{4}[-]{1}\d{4}$/m',
                ],
                "start_date" => [
                    "required",
                    "date",
                    "unique:academic_sessions,start_date",
                ],
                "end_date" => [
                    "required",
                    "date",
                    "unique:academic_sessions,end_date",
                    "after:start_date",
                ],
            ],
            $messages,
        );

        //check if date range is unique
        $validateDateRange = $this->dateOverlaps(
            $data["start_date"],
            $data["end_date"],
            AcademicSession::class,
        );

        //if date range is not unique throw validation exception
        if ($validateDateRange) {
            throw ValidationException::withMessages([
                "start_date" => ["Date range overlaps with another period"],
                "end_date" => ["Date range overlaps with another period"],
            ]);
        }

        $newAcademicSession = AcademicSession::create([
            "name" => $data["name"],
            "start_date" => $data["start_date"],
            "end_date" => $data["end_date"],
        ]);

        // Log activity
        \activity()
            ->causedBy(auth()->user())
            ->on($newAcademicSession)
            ->log("Created Academic Session");

        return redirect()
            ->route("academic-session.index")
            ->with("success", "Academic Session Created!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcademicSession  $academicSession
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(
        AcademicSession $academicSession,
    ): \Illuminate\Http\RedirectResponse {
        try {
            $academicSessionName = $academicSession->name;
            $academicSession->delete();

            // Log activity
            \activity()
                ->causedBy(auth()->user())
                ->on($academicSession)
                ->withProperties([
                    "academic_session_name" => $academicSessionName,
                ])
                ->log("Deleted Academic Session");

            return redirect()
                ->route("academic-session.index")
                ->with("success", "Academic session deleted!");
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return redirect()
                    ->route("academic-session.index")
                    ->with(
                        "error",
                        "Academic session can not be deleted because some resources are dependent on it!",
                    );
            }
            throw $e;
        }
    }

    /**
     * validate Academic Session
     *
     * @param  mixed  $request
     * @param  mixed  $academicSession
     * @return array
     */
    private function validateAcademicSession(
        $request,
        $academicSession = null,
    ): array {
        $messages = [
            "name.required" => "This field is required",
            "name.unique" => "Record exists",
            "name.regex" => "Academic session format is invalid",
        ];

        return $request->validate(
            [
                "name" => [
                    "required",
                    "string",
                    Rule::unique("academic_sessions")->ignore($academicSession),
                    'regex:/^\d{4}[-]{1}\d{4}$/m',
                ],
                "start_date" => [
                    "required",
                    "date",
                    Rule::unique("academic_sessions")->ignore($academicSession),
                ],
                "end_date" => [
                    "required",
                    "date",
                    Rule::unique("academic_sessions")->ignore($academicSession),
                    "after:start_date",
                ],
            ],
            $messages,
        );
    }

    /**
     * Display a edit view of the resource.
     *
     * @param  AcademicSession  $academicSession
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(
        AcademicSession $academicSession,
    ): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory {
        // // Log activity
        // \activity()->causedBy(auth()->user())
        //     ->on($academicSession)
        //     ->log("Requested Academic Session edit form");

        return view("academic-session.edit", compact("academicSession"));
    }

    /**
     * update Academic Session
     *
     * @param  AcademicSession  $academicSession
     * @param  Request  $request
     * @return Illuminate\Routing\Redirector
     */
    public function update(
        AcademicSession $academicSession,
        Request $request,
    ): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse {
        $data = $this->validateAcademicSession($request, $academicSession);

        //check if date range is unique
        $dateOverlaps = $this->dateOverlaps(
            $data["start_date"],
            $data["end_date"],
            AcademicSession::class,
            $academicSession,
        );

        if ($dateOverlaps) {
            return back()->with(
                "error",
                "Date range overlaps with another Academic session",
            );
        }

        $academicSession->update($data);

        // // Log activity
        // \activity()->causedBy(auth()->user())
        //     ->on($academicSession)
        //     ->log("Updated Academic Session");

        return redirect()
            ->route("academic-session.index")
            ->with("success", "Academic Session Updated!");
    }
}

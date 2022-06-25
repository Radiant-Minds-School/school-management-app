<?php

namespace App\Http\Controllers\Auth\Guardian;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GuardianLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.guardian.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\GuardianLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GuardianLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // return redirect(route('classroom.show.branch', ['classroom' => $teachersClassroom?->classroom, 'branch' => $teachersClassroom?->branch]))->with('success', "Welcome {$request->user('teacher')->first_name} {$request->user('teacher')->last_name}");
        return redirect(route('wards'))->with('success', "Welcome {$request->user('guardian')->first_name} {$request->user('guardian')->last_name}");
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('guardian')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

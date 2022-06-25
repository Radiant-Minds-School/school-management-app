<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use  Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::all();
        $users->map(function ($user) {
            if ($user->last_seen) {
                $lastSeen = $user->last_seen;

                // convert to carbon instance
                $lastSeen = Carbon::createFromFormat('Y-m-d H:i:s', $lastSeen);

                // Convert to human diff format
                $lastSeen = $lastSeen->diffForHumans();

                $user->last_seen = $lastSeen;
            }
        });

        return view('user.index', compact('users'));
    }

    /**
     * Verify a new user.
     *
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(User $user)
    {
        $this->authorize('verify', $user);

        $user->is_verified = true;
        $user->is_active = true;

        $user->save();

        return back()->with('success', 'User Verified');
    }

    /**
     * Toggle the user status between active and inactive states.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);

        if ($user->isActive()) {
            $user->is_active = false;

            //set action to deactivated
            $action = 'deactivated';
        } else {
            $user->is_active = true;

            //set action to activated
            $action = 'activated';
        }

        $user->save();

        return back()->with('success', 'User '.$action.'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('user.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
        ]);

        $user->update($data);

        return redirect()->back()->with('success', 'User updated!');
    }

    /**
     * Update password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('updatePassword', $user);

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        //if password does not match the current password
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['Password does not match current password']]);
        }

        $user->password = bcrypt($data['new_password']);
        $user->save();

        return redirect()->back()->with('success', 'Password updated!');
    }

    /**
     * store user Signature
     *
     * @param  User  $user
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSignature(User $user, Request $request)
    {
        $this->authorize('storeSignature', $user);

        $request->validate([
            'signature' => ['required', 'image', 'unique:users,signature,except,id', 'mimes:jpg', 'max:1000'],
        ]);

        //create name from first and last name
        $signatureName = $user->first_name.$user->last_name.'.'.$request->signature->extension();
        $path = $request->file('signature')->storeAs('public/users/signatures', $signatureName);
        Image::make($request->signature->getRealPath())->fit(400, 400)->save(storage_path('app/'.$path));

        //update signature in the database
        $filePath = 'storage/users/signatures/'.$signatureName;
        $user->signature = $filePath;
        $user->save();

        return back()->with('success', 'Signature uploaded successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted!');
    }

    /**
     * Set HOS
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setHos(User $user)
    {
        if (! $user->isActive()) {
            return redirect()->back()->with('error', 'User is not active');
        }

        $currentHOSs = User::where('is_hos', true)->get();

        foreach ($currentHOSs as $currentHOS) {
            if (! is_null($currentHOS)) {
                $currentHOS->is_hos = false;
                $currentHOS->save();
            }
        }

        $user->is_hos = true;
        $user->save();

        return redirect()->back()->with('success', "{$user->first_name} {$user->last_name} is now the HOS");
    }
}

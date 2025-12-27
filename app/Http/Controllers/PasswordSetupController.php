<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class PasswordSetupController extends Controller
{
    public function show(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid.');
        }

        if ($user->password_set_at) {
            return redirect()->route('login')->with('status', 'Password already set. Please login.');
        }

        return Inertia::render('auth/SetupPassword', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_set_at' => now(),
        ]);

        return redirect()->route('login')->with('status', 'Password set successfully. You can now login.');
    }
}

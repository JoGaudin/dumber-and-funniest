<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\League;
use App\Models\Comment;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Notifications\UserInvitation;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Index', [
            'users' => User::all(),
            'leagues' => League::all(),
            'comments' => Comment::all(),
        ]);
    }

    public function sendInvitation(User $user)
    {
        if ($user->password_set_at) {
            return redirect()->back()->with('error', 'User already has a password set.');
        }

        $url = URL::temporarySignedRoute(
            'password.setup',
            now()->addHours(24),
            ['user' => $user->id]
        );

        $user->notify(new UserInvitation($url));

        return redirect()->back()->with('success', 'Invitation sent successfully.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,user',
            'picture' => 'nullable|image|max:2048',
        ]);

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('profile-photos', 'public');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'picture' => $picturePath,
            'password' => Hash::make(Str::random(12)),
            'password_set_at' => null,
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }
}

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
use App\Models\LeagueUser;
use App\Models\CommentType;
use App\Models\LeagueUserCommentTypeScore;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Index', [
            'users' => User::all(),
            'leagues' => League::with('users')->get(),
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

    public function storeLeague(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('league-logos', 'public');
        }

        $league = League::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'logo' => $logoPath,
        ]);

        if (!empty($validated['users'])) {
            $commentTypes = CommentType::all();
            foreach ($validated['users'] as $userId) {
                $leagueUser = LeagueUser::create([
                    'league_id' => $league->league_id,
                    'user_id' => $userId,
                ]);

                foreach ($commentTypes as $type) {
                    LeagueUserCommentTypeScore::create([
                        'league_user_id' => $leagueUser->league_user_id,
                        'comment_type_id' => $type->comment_type_id,
                        'score' => 0,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'League created successfully.');
    }

    public function updateLeague(Request $request, League $league)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        if ($request->hasFile('logo')) {
            if ($league->logo) {
                Storage::disk('public')->delete($league->logo);
            }
            $league->logo = $request->file('logo')->store('league-logos', 'public');
        }

        $league->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        if (isset($validated['users'])) {
            $currentUsers = $league->users()->pluck('users.id')->toArray();
            $newUsers = array_diff($validated['users'], $currentUsers);
            $removedUsers = array_diff($currentUsers, $validated['users']);

            // Remove users
            if (!empty($removedUsers)) {
                LeagueUser::where('league_id', $league->league_id)
                    ->whereIn('user_id', $removedUsers)
                    ->delete();
            }

            // Add new users
            if (!empty($newUsers)) {
                $commentTypes = CommentType::all();
                foreach ($newUsers as $userId) {
                    $leagueUser = LeagueUser::create([
                        'league_id' => $league->league_id,
                        'user_id' => $userId,
                    ]);

                    foreach ($commentTypes as $type) {
                        LeagueUserCommentTypeScore::create([
                            'league_user_id' => $leagueUser->league_user_id,
                            'comment_type_id' => $type->comment_type_id,
                            'score' => 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'League updated successfully.');
    }
}

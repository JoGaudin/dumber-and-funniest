<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\League;
use App\Models\Comment;

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
}

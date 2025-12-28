<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeagueRequest;
use App\Http\Requests\UpdateLeagueRequest;
use App\Models\League;
use Inertia\Inertia;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeagueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(\Illuminate\Http\Request $request, League $league)
    {
        $commentTypes = \App\Models\CommentType::all();

        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc');

        $rankings = $league->leagueUsers()
            ->with(['user', 'scores.type'])
            ->when($sort === 'name', function ($query) use ($direction) {
                $query->join('users', 'users.id', '=', 'league_user.user_id')
                    ->select('league_user.*')
                    ->orderBy('users.name', $direction);
            })
            ->when(is_numeric($sort), function ($query) use ($sort, $direction) {
                $query->addSelect([
                    'sort_score' => \App\Models\LeagueUserCommentTypeScore::select('score')
                        ->whereColumn('league_user_id', 'league_user.league_user_id')
                        ->where('comment_type_id', $sort)
                        ->limit(1)
                ])->orderBy('sort_score', $direction);
            })
            ->get()
            ->map(function ($leagueUser) {
                return [
                    'user_id' => $leagueUser->user->id,
                    'name' => $leagueUser->user->name,
                    'avatar' => $leagueUser->user->avatar,
                    'scores' => $leagueUser->scores->mapWithKeys(function ($score) {
                        return [$score->comment_type_id => $score->score];
                    }),
                ];
            });

        $comments = $league->comments()
            ->with(['user', 'type', 'submitter'])
            ->latest()
            ->get()
            ->map(function ($comment) {
                $userAnswer = \App\Models\Answer::where('comment_id', $comment->comment_id)
                    ->where('user_id', auth()->id())
                    ->first();

                return array_merge($comment->toArray(), [
                    'user_answer' => $userAnswer ? $userAnswer->answer : null,
                    'user' => $comment->user,
                    'type' => $comment->type,
                    'submitter' => $comment->submitter,
                ]);
            });

        return Inertia::render('leagues/Show', [
            'league' => $league,
            'commentTypes' => $commentTypes,
            'rankings' => $rankings,
            'comments' => $comments,
            'filters' => [
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(League $league)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeagueRequest $request, League $league)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(League $league)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Answer;

class AnswerController extends Controller
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
    public function store(StoreAnswerRequest $request)
    {
        $validated = $request->validated();
        $userId = auth()->id();

        // Check if user already answered this comment
        $exists = Answer::where('comment_id', $validated['comment_id'])
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return back()->withErrors(['answer' => 'You have already answered this comment.']);
        }

        $comment = \App\Models\Comment::findOrFail($validated['comment_id']);

        // Create answer
        Answer::create([
            'league_id' => $validated['league_id'],
            'user_id' => $userId,
            'comment_id' => $validated['comment_id'],
            'answer' => $validated['answer'],
        ]);

        // Update comment counts
        if ($validated['answer'] === 'validation') {
            $comment->increment('validated_nb');
        } else {
            $comment->increment('revocation_nb');
        }

        // Update score in league_user_comment_type_score
        $leagueUser = \App\Models\LeagueUser::where('league_id', $comment->league_id)
            ->where('user_id', $comment->user_id)
            ->first();

        if ($leagueUser) {
            $scoreChange = $validated['answer'] === 'validation' ? 1 : -1;

            $scoreRecord = \App\Models\LeagueUserCommentTypeScore::where('league_user_id', $leagueUser->league_user_id)
                ->where('comment_type_id', $comment->comment_type_id)
                ->first();

            if ($scoreRecord) {
                \DB::table('league_user_comment_type_score')
                    ->where('league_user_id', $leagueUser->league_user_id)
                    ->where('comment_type_id', $comment->comment_type_id)
                    ->update(['score' => \DB::raw("score + {$scoreChange}")]);
            } else {
                \App\Models\LeagueUserCommentTypeScore::create([
                    'league_user_id' => $leagueUser->league_user_id,
                    'comment_type_id' => $comment->comment_type_id,
                    'score' => $scoreChange,
                ]);
            }
        }

        return back()->with('success', 'Answer submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerRequest $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        //
    }
}

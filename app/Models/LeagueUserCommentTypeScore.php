<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueUserCommentTypeScore extends Model
{
    /** @use HasFactory<\Database\Factories\LeagueUserCommentTypeScoreFactory> */
    use HasFactory;

    protected $fillable = [
        'league_user_id',
        'comment_type_id',
        'score',
    ];

    protected $table = 'league_user_comment_type_score';

    public function type()
    {
        return $this->belongsTo(CommentType::class, 'comment_type_id');
    }

    public function leagueUser()
    {
        return $this->belongsTo(LeagueUser::class, 'league_user_id');
    }
}

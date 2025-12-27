<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $primaryKey = 'answer_id';

    protected $fillable = [
        'league_id',
        'user_id',
        'comment_id',
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentType extends Model
{
    protected $primaryKey = 'comment_type_id';

    protected $fillable = ['name', 'emoji'];

    public function scores()
    {
        return $this->hasMany(LeagueUserCommentTypeScore::class, 'comment_type_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_type_id');
    }

    public function getEmojiAttribute($value)
    {
        return \App\Enums\CommentTypeEmoji::getEmoji($this->name);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'league_id',
        'user_id',
        'comment_type_id',
        'comment',
        'submit_by',
        'validated_nb',
        'revocation_nb',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submit_by');
    }

    public function type()
    {
        return $this->belongsTo(CommentType::class, 'comment_type_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }
}

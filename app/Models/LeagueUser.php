<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueUser extends Model
{
    /** @use HasFactory<\Database\Factories\LeagueUserFactory> */
    use HasFactory;

    protected $fillable = [
        'league_id',
        'user_id',
    ];

    public $timestamps = false;

    protected $table = 'league_user';
    protected $primaryKey = 'league_user_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function scores()
    {
        return $this->hasMany(LeagueUserCommentTypeScore::class, 'league_user_id');
    }
}

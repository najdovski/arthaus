<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'started_at',
        'finished_at',
        'description',
    ];

    /**
     * Get the user that created the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity access token (used for accessing the activities from outside the app)
     */
    public function activityAccessToken()
    {
        return $this->hasMany(ActivityAccessToken::class);
    }
}

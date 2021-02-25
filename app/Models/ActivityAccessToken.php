<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityAccessToken extends Model
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
        'email',
        'url_token',
    ];

    /**
     * Get the activity
     */
    public function user()
    {
        return $this->belongsTo(Activity::class);
    }

    // Disable timestamps
    public $timestamps = false;
}

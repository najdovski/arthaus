<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityAccessToken extends Model
{
    use HasFactory;

    /**
     * Get the activity
     */
    public function user()
    {
        return $this->belongsTo(Activity::class);
    }
}

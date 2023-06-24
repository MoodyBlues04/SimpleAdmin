<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'header',
        'text',
        'password',
        // 'creator_id'
    ];

    /**
     * Users who participate in the event
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}

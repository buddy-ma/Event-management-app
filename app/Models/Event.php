<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'start_date',
        'end_date',
        'address',
        'max_participants',
        'category',
        'status',
        'visibility',
        'is_online',
        'online_url'
    ];


    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function isHost(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function hasAvailableSpots(): bool
    {
        return $this->participants()->count() < $this->max_participants;
    }
}
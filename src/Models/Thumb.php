<?php

namespace Dealskoo\Thumb\Models;

use Dealskoo\Thumb\Events\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    protected $fillable = [
        'up',
        'down'
    ];

    protected $dispatchesEvents = [
        'updated' => Event::class
    ];

    public function thumbable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function thumber()
    {
        return $this->user();
    }

    public function scopeWithType(Builder $builder, string $type)
    {
        return $builder->where('thumbable_type', app($type)->getMorphClass());
    }
}

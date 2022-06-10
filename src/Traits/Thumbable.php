<?php

namespace Dealskoo\Thumb\Traits;

use Dealskoo\Thumb\Models\Thumb;
use Illuminate\Database\Eloquent\Model;

trait Thumbable
{
    public function isThumbedBy(Model $user)
    {
        if (is_a($user, config('auth.providers.users.model'))) {
            if ($this->relationLoaded('thumbers')) {
                return $this->thumbers->contains($user);
            }
            return $this->thumbers()->where('user_id', $user->getKey())->exists();
        }
        return false;
    }

    public function thumbs()
    {
        return $this->morphMany(Thumb::class, 'thumbable');
    }

    public function thumbsUp()
    {
        return $this->morphMany(Thumb::class, 'thumbable')->where('up', 1)->where('down', 0);
    }

    public function thumbsDown()
    {
        return $this->morphMany(Thumb::class, 'thumbable')->where('up', 0)->where('down', 1);
    }

    public function thumbers()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'thumbs', 'thumbable_id', 'user_id')->where('thumbable_type', $this->getMorphClass());
    }
}

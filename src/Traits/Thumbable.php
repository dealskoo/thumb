<?php

namespace Dealskoo\Thumb\Traits;

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

    public function thumbers()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'thumbs', 'thumbable_id', 'user_id')->where('thumbable_type', $this->getMorphClass());
    }
}

<?php

namespace Dealskoo\Thumb\Traits;

use Dealskoo\Thumb\Models\Thumb;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Thumber
{
    public function thumbUp(Model $model)
    {
        $attributes = [
            'thumbable_type' => $model->getMorphClass(),
            'thumbable_id' => $model->getKey(),
            'user_id' => $model->getKey()
        ];
        return Thumb::query()->where($attributes)->firstOr(function () use ($attributes) {
            return Thumb::unguard(function () use ($attributes) {
                if ($this->relationLoaded('thumbs')) {
                    $this->unsetRelation('thumbs');
                }
                return Thumb::query()->create(Arr::collapse([$attributes, ['up' => 1, 'down' => 0]]));
            });
        });
    }

    public function thumbDown(Model $model)
    {
        $attributes = [
            'thumbable_type' => $model->getMorphClass(),
            'thumbable_id' => $model->getKey(),
            'user_id' => $model->getKey()
        ];
        return Thumb::query()->where($attributes)->firstOr(function () use ($attributes) {
            return Thumb::unguard(function () use ($attributes) {
                if ($this->relationLoaded('thumbs')) {
                    $this->unsetRelation('thumbs');
                }
                return Thumb::query()->create(Arr::collapse([$attributes, ['up' => 0, 'down' => 1]]));
            });
        });
    }

    public function toggleThumb(Model $model)
    {
        return $this->hasThumbUp($model) ? $this->thumbDown($model) : $this->thumbUp($model);
    }

    public function hasThumb(Model $model)
    {
        $thumbs = $this->relationLoaded('thumbs') ? $this->thumbs : $this->thumbs();
        return $thumbs->where('thumbable_id', $model->getKey())->where('thumbable_type', $model->getMorphClass())->count() > 0;
    }

    public function hasThumbUp(Model $model)
    {
        $thumbs = $this->relationLoaded('thumbs') ? $this->thumbs : $this->thumbs();
        return $thumbs->where('thumbable_id', $model->getKey())->where('thumbable_type', $model->getMorphClass())->where('up', 1)->where('down', 0)->count() > 0;
    }

    public function hasThumbDown(Model $model)
    {
        $thumbs = $this->relationLoaded('thumbs') ? $this->thumbs : $this->thumbs();
        return $thumbs->where('thumbable_id', $model->getKey())->where('thumbable_type', $model->getMorphClass())->where('up', 0)->where('down', 1)->count() > 0;
    }

    public function thumbs()
    {
        return $this->hasMany(Thumb::class, 'user_id', $this->getKeyName());
    }

    public function getThumbItems(string $model)
    {
        return app($model)->whereHas('thumbers', function ($q) {
            return $q->where('user_id', $this->getKey());
        });
    }

    public function getThumbUpItems(string $model)
    {
        return app($model)->whereHas('thumbers', function ($q) {
            return $q->where('user_id', $this->getKey())->where('up', 1)->where('down', 0);
        });
    }

    public function getThumbDownItems(string $model)
    {
        return app($model)->whereHas('thumbers', function ($q) {
            return $q->where('user_id', $this->getKey())->where('up', 0)->where('down', 1);
        });
    }
}

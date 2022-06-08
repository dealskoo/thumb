<?php

namespace Dealskoo\Thumb\Tests;

use Dealskoo\Thumb\Traits\Thumbable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Thumbable;

    protected $fillable = ['name'];
}

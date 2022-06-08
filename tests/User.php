<?php

namespace Dealskoo\Thumb\Tests;

use Dealskoo\Thumb\Traits\Thumber;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Thumber;

    protected $fillable = ['name'];
}

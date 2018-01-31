<?php

namespace Vidovic\Rolly\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Vidovic\Rolly\Traits\RollyUserTrait;

class User extends Model
{
    use RollyUserTrait;

    protected $guarded = [];
}


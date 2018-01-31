<?php

namespace Vidovic\Rolly\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Vidovic\Rolly\Contracts\RollyRoleInterface;
use Vidovic\Rolly\Traits\RollyRolesTrait;

class RollyRole extends Model implements RollyRoleInterface
{
    use RollyRolesTrait;

    protected $table;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('rolly.tables.roles');
    }

}

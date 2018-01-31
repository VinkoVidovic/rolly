<?php

namespace Vidovic\Rolly\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Vidovic\Rolly\Contracts\RollyPermissionInterface;
use Vidovic\Rolly\Traits\RollyPermissionsTrait;

class RollyPermission extends Model implements RollyPermissionInterface
{
    use RollyPermissionsTrait;

    protected $guarded = [];

    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('rolly.tables.permissions');
    }
}

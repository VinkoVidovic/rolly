<?php

namespace Vidovic\Rolly\Traits;

use Illuminate\Support\Facades\Config;
use Vidovic\Rolly\Models\RollyRole;
use Vidovic\Rolly\Models\RollyPermission;
use Vidovic\Rolly\Models\RollyUser;

trait RollyPermissionsTrait
{

    public function roles()
    {
        return $this->belongsToMany(
            Config::get('rolly.models.role'),
            Config::get('rolly.tables.permission_role'),
            Config::get('rolly.foreign_keys.permission'),
            Config::get('rolly.foreign_keys.role')
        );
    }

    public function users()
    {
        dd(Config::get('rolly.models.user'));

        return $this->belongsToMany(
            Config::get('rolly.models.user'),
            Config::get('rolly.tables.permission_user'),
            Config::get('rolly.foreign_keys.permission'),
            Config::get('rolly.foreign_keys.user')
        );
    }
}

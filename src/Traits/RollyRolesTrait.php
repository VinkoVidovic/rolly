<?php

namespace Vidovic\Rolly\Traits;


use Illuminate\Support\Facades\Config;
use Vidovic\Rolly\Helper;


trait RollyRolesTrait
{

    public function users()
    {
        return $this->belongsToMany(
            Config::get('rolly.models.user'),
            Config::get('rolly.tables.role_user'),
            Config::get('rolly.foreign_keys.role'),
            Config::get('rolly.foreign_keys.user')
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Config::get('rolly.models.permission'),
            Config::get('rolly.tables.permission_role'),
            Config::get('rolly.foreign_keys.role'),
            Config::get('rolly.foreign_keys.permission')
        );
    }

    public function hasPermission($permission)
    {
        foreach ($this->permissions()->get() as $perm) {
            if (str_is($permission, $perm['name'])) {
                return true;
            }
        }
        return false;
    }

    public function attachPermission($permission)
    {
        $permission = Helper::getIdFor($permission, 'permission');

        $this->permissions()->attach($permission);

        return $this;
    }

    public function detachPermission($permission)
    {
        $permission = Helper::getIdFor($permission, 'permission');

        $this->permissions()->detach($permission);

        return $this;
    }

    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }

        return $this;
    }

    public function detachPermissions($permissions = null)
    {
        if (!$permissions) {
            $permissions = $this->permissions()->get();
        }

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }

        return $this;
    }

    public function syncPermissions($permissions)
    {
        $mappedPermissions = [];

        foreach ($permissions as $permission) {
            $mappedPermissions[] = Helper::getIdFor($permission, 'permission');
        }

        $this->permissions()->sync($mappedPermissions);

        return $this;
    }
}
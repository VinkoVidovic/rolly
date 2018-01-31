<?php

namespace Vidovic\Rolly\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Vidovic\Rolly\Helper;
use InvalidArgumentException;

trait RollyUserTrait
{
    public function roles()
    {
        $roles = $this->belongsToMany(
            Config::get('rolly.models.role'),
            Config::get('rolly.tables.role_user'),
            Config::get('rolly.foreign_keys.user'),
            Config::get('rolly.foreign_keys.role')
        );

        return $roles;

    }

    public function permissions()
    {
        $permissions = $this->belongsToMany(
            Config::get('rolly.models.permission'),
            Config::get('rolly.tables.permission_user'),
            Config::get('rolly.foreign_keys.user'),
            Config::get('rolly.foreign_keys.permission')
        );

        return $permissions;
    }

    public function hasRole($name)
    {
        $name = Helper::standardize($name);

        foreach ($this->roles()->get() as $role) {
            if (str_is($name, $role['name'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permission = Helper::standardize($permission);

        foreach ($this->permissions()->get() as $perm) {
            if (str_is($permission, $perm['name'])) {
                return true;
            }
        }

        foreach ($this->roles()->get() as $role) {
            $role = Helper::hidrateModel(Config::get('rolly.models.role'), $role);

            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function attachModel($relationship, $object)
    {
        if (!Helper::isValidRelationship($relationship)) {
            throw new InvalidArgumentException;
        }

        $objectType = Str::singular($relationship);
        $object = Helper::getIdFor($object, $objectType);

        $this->$relationship()->attach($object);

        return $this;
    }

    public function detachModel($relationship, $object)
    {
        if (!Helper::isValidRelationship($relationship)) {
            throw new InvalidArgumentException;
        }

        $objectType = Str::singular($relationship);
        $relationshipQuery = $this->$relationship();

        $object = Helper::getIdFor($object, $objectType);
        $relationshipQuery->detach($object);

        return $this;
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param  mixed  $role
     * @return static
     */
    public function attachRole($role)
    {
        return $this->attachModel('roles', $role);
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param  mixed  $role
     * @return static
     */
    public function detachRole($role)
    {
        return $this->detachModel('roles', $role);
    }

    /**
     * Attach multiple roles to a user.
     *
     * @param  mixed  $roles
     * @return static
     */
    public function attachRoles($roles = [])
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }

        return $this;
    }

    /**
     * Detach multiple roles from a user.
     *
     * @param  mixed  $roles
     * @return static
     */
    public function detachRoles($roles = [])
    {
        if (empty($roles)) {
            $roles = $this->roles()->get();
        }

        foreach ($roles as $role) {
            $this->detachRole($role);
        }

        return $this;
    }
    /**
     * Sync roles to the user.
     *
     * @param  array  $roles
     * @return static
     */
    public function syncRoles($roles = [])
    {
        return $this->syncModels('roles', $roles);
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param  mixed  $permission
     * @return static
     */
    public function attachPermission($permission)
    {
        return $this->attachModel('permissions', $permission);
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param  mixed  $permission
     * @return static
     */
    public function detachPermission($permission)
    {
        return $this->detachModel('permissions', $permission);
    }

    /**
     * Attach multiple permissions to a user.
     *
     * @param  mixed  $permissions
     * @return static
     */
    public function attachPermissions($permissions = [])
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }

        return $this;
    }

    /**
     * Detach multiple permissions from a user.
     *
     * @param  mixed  $permissions
     * @return static
     */
    public function detachPermissions($permissions = [])
    {
        if (!$permissions) {
            $permissions = $this->permissions()->get();
        }

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }

        return $this;
    }

    /**
     * Sync permissions to the user.
     *
     * @param  array  $permissions
     * @return static
     */
    public function syncPermissions($permissions = [])
    {
        return $this->syncModels('permissions', $permissions);
    }

    /**
     * Return all the user permissions.
     *
     * @return boolean
     */
    public function allPermissions()
    {
        $roles = $this->roles()->with('permissions')->get();

        $roles = $roles->flatMap(function ($role) {
            return $role->permissions;
        });

        return $this->permissions->merge($roles)->unique('name');
    }

}
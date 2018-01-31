<?php

namespace Vidovic\Rolly\Contracts;


interface RollyRoleInterface
{
    /**
     * Many-to-Many relations with the permission model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * Many-to-Many relations with user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

    /**
     * Checks if the role has a permission by its name.
     *
     * @param  string|array  $permission       Permission name or array of permission names.
     * @return bool
     */
    public function hasPermission($permission);

    /**
     * Save the inputted permissions.
     *
     * @param  mixed  $permissions
     * @return array
     */
    public function syncPermissions($permissions);

    /**
     * Attach permission to current role.
     *
     * @param  object|array  $permission
     * @return void
     */
    public function attachPermission($permission);

    /**
     * Detach permission from current role.
     *
     * @param  object|array  $permission
     * @return void
     */
    public function detachPermission($permission);

    /**
     * Attach multiple permissions to current role.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function attachPermissions($permissions);

    /**
     * Detach multiple permissions from current role
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function detachPermissions($permissions);
}
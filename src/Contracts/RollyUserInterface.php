<?php

namespace Vidovic\Rolly\Contracts;


interface RollyUserInterface
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Many-to-Many relations with Permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * Checks if the user has a role by its name.
     *
     * @param  string|array  $name       Role name or array of role names.
     * @return bool
     */
    public function hasRole($name);

    /**
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @return bool
     */
    public function hasPermission($permission);

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param  mixed  $role
     * @return static
     */
    public function attachRole($role);

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param  mixed  $role
     * @return static
     */
    public function detachRole($role);

    /**
     * Attach multiple roles to a user.
     *
     * @param  mixed  $roles
     * @return static
     */
    public function attachRoles($roles = []);

    /**
     * Detach multiple roles from a user.
     *
     * @param  mixed  $roles
     * @return static
     */
    public function detachRoles($roles = []);

    /**
     * Sync roles to the user.
     *
     * @param  array  $roles
     * @return static
     */
    public function syncRoles($roles = []);

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param  mixed  $permission
     * @return static
     */
    public function attachPermission($permission);

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param  mixed  $permission
     * @return static
     */
    public function detachPermission($permission);

    /**
     * Attach multiple permissions to a user.
     *
     * @param  mixed  $permissions
     * @return static
     */
    public function attachPermissions($permissions = []);

    /**
     * Detach multiple permissions from a user.
     *
     * @param  mixed  $permissions
     * @return static
     */
    public function detachPermissions($permissions = []);

    /**
     * Sync roles to the user.
     *
     * @param  array  $permissions
     * @return static
     */
    public function syncPermissions($permissions = []);

    /**
     * Return all the user permissions.
     *
     * @return boolean
     */
    public function allPermissions();
}
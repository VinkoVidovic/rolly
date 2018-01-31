<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rolly Models
    |--------------------------------------------------------------------------
    |
    | These are the models used by Rolly to define the roles, permissions and teams.
    | If you want the Rolly models to be in a different namespace or
    | to have a different name, you can do it here.
    |
    */
    'models' => [
        /**
         * Role model
         */
        'role' => 'App\Role',

        /**
         * Permission model
         */
        'permission' => 'App\Permission',

        /**
         * User model
         */
        'user' => 'App\User',
    ],
    /*
    |--------------------------------------------------------------------------
    | Rolly Tables
    |--------------------------------------------------------------------------
    |
    | These are the tables used by Rolly to store all the authorization data.
    |
    */
    'tables' => [
        /**
         * Roles table.
         */
        'roles' => 'roles',

        /**
         * Permissions table.
         */
        'permissions' => 'permissions',

        /**
         * Role - User intermediate table.
         */
        'role_user' => 'role_user',

        /**
         * Permission - User intermediate table.
         */
        'permission_user' => 'permission_user',

        /**
         * Permission - Role intermediate table.
         */
        'permission_role' => 'permission_role',

        'users' => 'users'
    ],
    /*
    |--------------------------------------------------------------------------
    | Rolly Foreign Keys
    |--------------------------------------------------------------------------
    |
    | These are the foreign keys used by laratrust in the intermediate tables.
    |
    */
    'foreign_keys' => [
        /**
         * User foreign key on Rolly's role_user and permission_user tables.
         */
        'user' => 'user_id',

        /**
         * Role foreign key on Rolly's role_user and permission_role tables.
         */
        'role' => 'role_id',

        /**
         * Role foreign key on Rolly's permission_user and permission_role tables.
         */
        'permission' => 'permission_id',
    ],
];
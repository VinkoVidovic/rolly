<?php

namespace Vidovic\Rolly\Tests;

use Vidovic\Rolly\Tests\Models\Role;
use Vidovic\Rolly\Tests\LaratrustTestCase;
use Vidovic\Rolly\Tests\Models\Permission;
use Illuminate\Support\Facades\Config;

class RollyRoleTest extends RollyTestCase
{
    protected $role;

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
        $this->role = Role::create(['name' => 'role']);
    }

    public function testAccessUsersRelationshipAsAttribute()
    {
        $this->assertEmpty($this->role->users);
    }

    /** @test */
    function user_relationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $this->role->users());
    }

    /** @test */
    public function permissions_relationship()
    {
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $this->role->permissions());
    }


    /** @test */
    function a_role_has_a_permission()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permA = Permission::create(['name' => 'permission_a']);
        $permB = Permission::create(['name' => 'permission_b']);

        $this->role->permissions()->attach([$permA->id, $permB->id]);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertTrue($this->role->hasPermission('permission_a'));
        $this->assertTrue($this->role->hasPermission('permission_b'));
        $this->assertFalse($this->role->hasPermission('permission_c'));
    }

    /** @test */
    function we_can_attach_a_permission_to_a_role()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permA = Permission::create(['name' => 'permission_a']);
        $permB = Permission::create(['name' => 'permission_b']);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->attachPermission($permA));
        $this->assertCount(1, $this->role->permissions()->get()->toArray());

        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->attachPermission($permB->toArray()));
        $this->assertCount(2, $this->role->permissions()->get()->toArray());
    }

    /** @test */
    function we_can_attach_multiple_permissions_to_a_role()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $perms = [
            Permission::create(['name' => 'permission_a']),
            Permission::create(['name' => 'permission_b']),
            Permission::create(['name' => 'permission_c']),
        ];
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->attachPermissions($perms));
        $this->assertCount(3, $this->role->permissions()->get()->toArray());
    }

    /** @test */
    function we_can_detach_a_permission_from_a_role()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permA = Permission::create(['name' => 'permission_a']);
        $permB = Permission::create(['name' => 'permission_b']);

        $this->role->permissions()->attach([$permA->id, $permB->id]);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->detachPermission($permA));
        $this->assertCount(1, $this->role->permissions()->get()->toArray());
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->detachPermission($permB->toArray()));
        $this->assertCount(0, $this->role->permissions()->get()->toArray());
    }

    /** @test */
    function we_can_detach_a_multiple_permissions_from_a_role()
    {
        /*
       |------------------------------------------------------------
       | Set
       |------------------------------------------------------------
       */
        $perms = [
            Permission::create(['name' => 'permission_a']),
            Permission::create(['name' => 'permission_b']),
            Permission::create(['name' => 'permission_c']),
        ];
        $this->role->attachPermissions($perms);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->detachPermissions($perms));
        $this->assertCount(0, $this->role->permissions()->get()->toArray());
    }

    /** @test */
    function we_can_detach_all_permissions_from_a_role()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $perms = [
            Permission::create(['name' => 'permission_a']),
            Permission::create(['name' => 'permission_b']),
            Permission::create(['name' => 'permission_c']),
        ];
        $this->role->attachPermissions($perms);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->detachPermissions());
        $this->assertCount(0, $this->role->permissions()->get()->toArray());
    }

    /** @test */
    function we_can_synchronize_permissions_to_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $perms = [
            Permission::create(['name' => 'permission_a'])->id,
            Permission::create(['name' => 'permission_b'])->id,
        ];
        $this->role->attachPermission(Permission::create(['name' => 'permission_c']));
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\Role', $this->role->syncPermissions($perms));
        $this->assertCount(2, $this->role->permissions()->get()->toArray());
        $this->role->syncPermissions([]);
        $this->role->syncPermissions(['permission_a', 'permission_b']);
        $this->assertCount(2, $this->role->permissions()->get()->toArray());
    }
}
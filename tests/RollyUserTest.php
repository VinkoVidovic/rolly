<?php

namespace Vidovic\Rolly\Tests;

use Illuminate\Support\Str;
use Mockery as m;
use Vidovic\Rolly\Tests\Models\Role;
use Vidovic\Rolly\Tests\Models\User;
use Vidovic\Rolly\Tests\RollyTestCase;
use Vidovic\Rolly\Tests\Models\Permission;

class RollyUserTest extends RollyTestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->migrate();

        $this->user = User::create(['name' => 'test', 'email' => 'test@test.com']);
    }

    /** @test */
    function roles_relationship()
    {
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Relations\BelongsToMany',
            $this->user->roles()
        );
    }

    /** @test */
    function permissions_relationship()
    {
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Relations\BelongsToMany',
            $this->user->permissions()
        );
    }

    /** @test */
    function user_has_a_role()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $roleA = Role::create(['name' => 'role_a']);
        $roleB = Role::create(['name' => 'role_b']);

        $this->user->roles()->attach([$roleA->id, $roleB->id]);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertTrue($this->user->hasRole('role_a'));
        $this->assertTrue($this->user->hasRole('role_b'));
        $this->assertFalse($this->user->hasRole('role_c'));
    }

    /** @test */
    function user_has_a_permission()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $roleA = Role::create(['name' => 'role_a'])
            ->attachPermission(Permission::create(['name' => 'permission_a']));

        $roleB = Role::create(['name' => 'role_b'])
            ->attachPermission(Permission::create(['name' => 'permission_b']));

        $this->user->roles()->attach([
            $roleA->id,
            $roleB->id
        ]);

        $this->user->permissions()->attach([
            Permission::create(['name' => 'permission_c'])->id,
            Permission::create(['name' => 'permission_d'])->id,
        ]);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertTrue($this->user->hasPermission('permission_a'));
        $this->assertTrue($this->user->hasPermission('permission_b'));
        $this->assertTrue($this->user->hasPermission('permission_c'));
        $this->assertTrue($this->user->hasPermission('permission_d'));
        $this->assertFalse($this->user->hasPermission('permission_e'));
    }

    /** @test */
    function we_can_attach_role_to_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $role = Role::create(['name' => 'role_a']);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        // Can attach role by passing an object
        $this->assertWasAttached('role', $this->user->attachRole($role));
        // Can attach role by passing an id
        $this->assertWasAttached('role', $this->user->attachRole($role->id));
        // Can attach role by passing an array with 'id' => $id
        $this->assertWasAttached('role', $this->user->attachRole($role->toArray()));
        // Can attach role by passing the role name
        $this->assertWasAttached('role', $this->user->attachRole('role_a'));

        $this->assertWasAttached('role', $this->user->attachRole($role));


    }

    /** @test */
    function we_can_detach_role_from_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $role = Role::create(['name' => 'role_a']);
        $this->user->roles()->attach($role->id);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        // Can attach role by passing an object
        $this->assertWasDetached('role', $this->user->detachRole($role), $role);
        // Can detach role by passing an id
        $this->assertWasDetached('role', $this->user->detachRole($role->id), $role);
        // Can detach role by passing an array with 'id' => $id
        $this->assertWasDetached('role', $this->user->detachRole($role->toArray()), $role);
        // Can detach role by passing the role name
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $this->user->detachRole('role_a'));
        $this->assertEquals(0, $this->user->roles()->count());
    }

    /** @test */
    function we_can_attach_multiple_roles_to_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Vidovic\Rolly\Tests\Models\User')->makePartial();
        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $user->shouldReceive('attachRole')->with(m::anyOf(1, 2, 3))->times(3);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->attachRoles([1, 2, 3]));
    }

    /** @test */
    function we_can_detach_multiple_roles_from_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Vidovic\Rolly\Tests\Models\User')->makePartial();
        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $user->shouldReceive('roles->get')->andReturn([1, 2, 3])->once();
        $user->shouldReceive('detachRole')->with(m::anyOf(1, 2, 3))->times(6);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->detachRoles([1, 2, 3]));
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->detachRoles([]));

    }

    /** @test */
    function we_can_attach_a_permission_to_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permission = Permission::create(['name' => 'permission_a']);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        // Can attach permission by passing an object
        $this->assertWasAttached('permission', $this->user->attachPermission($permission));
        // Can attach permission by passing an id
        $this->assertWasAttached('permission', $this->user->attachPermission($permission->id));
        // Can attach permission by passing an array with 'id' => $id
        $this->assertWasAttached('permission', $this->user->attachPermission($permission->toArray()));
        // Can attach permission by passing the permission name
        $this->assertWasAttached('permission', $this->user->attachPermission('permission_a'));
    }

    /** @test */
    function we_can_detach_a_permission_from_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $permission = Permission::create(['name' => 'permission_a']);
        $this->user->permissions()->attach($permission->id);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        // Can attach permission by passing an object
        $this->assertWasDetached('permission', $this->user->detachPermission($permission), $permission);
        // Can detach permission by passing an id
        $this->assertWasDetached('permission', $this->user->detachPermission($permission->id), $permission);
        // Can detach permission by passing an array with 'id' => $id
        $this->assertWasDetached('permission', $this->user->detachPermission($permission->toArray()), $permission);
        // Can detach permission by passing the permission name
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $this->user->detachPermission('permission_a'));
        $this->assertEquals(0, $this->user->permissions()->count());
    }

    /** @test */
    function we_can_attach_multiple_permissions_to_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Vidovic\Rolly\Tests\Models\User')->makePartial();
        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $user->shouldReceive('attachPermission')->with(m::anyOf(1, 2, 3))->times(3);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->attachPermissions([1, 2, 3]));
    }

    /** @test */
    function we_can_detach_multiple_permissions_from_a_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $user = m::mock('Vidovic\Rolly\Tests\Models\User')->makePartial();
        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $user->shouldReceive('permissions->get')->andReturn([1, 2, 3])->once();
        $user->shouldReceive('detachPermission')->with(m::anyOf(1, 2, 3))->times(6);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->detachPermissions([1, 2, 3]));
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $user->detachPermissions([]));
    }

    /** @test */
    public function we_can_return_all_permissions_from_user()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $roleA = Role::create(['name' => 'role_a']);
        $roleB = Role::create(['name' => 'role_b']);

        $permissionA = Permission::create(['name' => 'permission_a']);
        $permissionB = Permission::create(['name' => 'permission_b']);
        $permissionC = Permission::create(['name' => 'permission_c']);

        $roleA->attachPermissions([$permissionA, $permissionB]);
        $roleB->attachPermissions([$permissionB, $permissionC]);
        $this->user->attachPermissions([$permissionB, $permissionC]);
        $this->user->attachRoles([$roleA, $roleB]);
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(
            ['permission_a', 'permission_b', 'permission_c'],
            $this->user->allPermissions()->sortBy('name')->pluck('name')->all()
        );
    }

    protected function assertWasAttached($objectName, $result)
    {
        $relationship = Str::plural($objectName);
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $result);
        $this->assertEquals(1, $this->user->$relationship()->count());
        $this->user->$relationship()->sync([]);
    }

    protected function assertWasDetached($objectName, $result, $toReAttach)
    {
        $relationship = Str::plural($objectName);
        $this->assertInstanceOf('Vidovic\Rolly\Tests\Models\User', $result);
        $this->assertEquals(0, $this->user->$relationship()->count());
        $this->user->$relationship()->attach($toReAttach->id);
    }
}
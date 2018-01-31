<?php

namespace Vidovic\Rolly;

use Vidovic\Rolly\Tests\RollyTestCase;
use Vidovic\Rolly\Tests\Models\Permission;
use Illuminate\Support\Facades\Config;

class RollyPermissionTest extends RollyTestCase
{
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
        $this->permission = new Permission();
    }

    /** @test */
    function users_relationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $this->permission->users());
    }

    /** @test */
    function roles_relationship()
    {
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $this->permission->roles());
    }
}
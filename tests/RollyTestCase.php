<?php
namespace Vidovic\Rolly\Tests;
use Orchestra\Testbench\TestCase;

class RollyTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Vidovic\Rolly\RollyServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('rolly.user_models.users', 'Vidovic\Rolly\Tests\Models\User');
        $app['config']->set('rolly.models', [
            'role' => 'Vidovic\Rolly\Tests\Models\Role',
            'permission' => 'Vidovic\Rolly\Tests\Models\Permission',
        ]);
    }


    public function migrate()
    {
        $migrations = [
            \Vidovic\Rolly\Tests\Migrations\UsersMigration::class,
            \Vidovic\Rolly\Tests\Migrations\RollySetupTables::class,
        ];
        foreach ($migrations as $migration) {
            (new $migration)->up();
        }
    }
}
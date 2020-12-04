<?php

namespace TarfinLabs\LaravelPos\Tests;

use Orchestra\Testbench\TestCase;
use TarfinLabs\LaravelPos\LaravelPosServiceProvider;
use TarfinLabs\LaravelPos\Tests\Fixtures\User;


abstract class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->artisan('migrate')->run();
    }

    protected function createBillable($description = 'hakan', array $options = []): User
    {
        return $this->createUser($description);
    }

    protected function createUser($description = 'hakan', array $options = []): User
    {
        return User::create(array_merge([
            'email' => "{$description}@tarfin.com",
            'name' => 'Hakan Özdemir',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ], $options));
    }

    protected function getPackageProviders($app)
    {
        return [LaravelPosServiceProvider::class];
    }
}

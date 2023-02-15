<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('labels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('labelable');
            $table->string('slug');
            $table->string('name');
            $table->string('description');
            $table->string('color');
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app): array
    {
        return [\PreemStudio\Labels\ServiceProvider::class];
    }
}

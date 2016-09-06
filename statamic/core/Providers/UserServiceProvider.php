<?php

namespace Statamic\Providers;

use Statamic\Data\Users\User;
use Statamic\Permissions\File\Role;
use Statamic\Data\Users\UserFactory;
use Illuminate\Support\ServiceProvider;
use Statamic\Permissions\File\UserGroup;
use Statamic\Permissions\File\RoleFactory;
use Statamic\Permissions\File\UserGroupFactory;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Statamic\Contracts\Data\Users\User', function() {
            return app(User::class);
        });

        $this->app->bind('Statamic\Contracts\Data\Users\UserFactory', function() {
            return app(UserFactory::class);
        });

        $this->app->bind('Statamic\Contracts\Permissions\Role', function() {
            return app(Role::class);
        });

        $this->app->singleton('Statamic\Contracts\Permissions\RoleFactory', function() {
            return app(RoleFactory::class);
        });

        $this->app->bind('Statamic\Contracts\Permissions\UserGroup', function() {
            return app(UserGroup::class);
        });

        $this->app->singleton('Statamic\Contracts\Permissions\UserGroupFactory', function() {
            return app(UserGroupFactory::class);
        });
    }
}

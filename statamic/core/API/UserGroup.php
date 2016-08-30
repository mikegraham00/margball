<?php

namespace Statamic\API;

use Statamic\Contracts\Permissions\Permissible;

class UserGroup
{
    /**
     * Get all the groups
     */
    public static function all()
    {
        return app('UserGroupsService')->all();
    }

    /**
     * Get a user group by ID
     *
     * @param string $id
     * @return \Statamic\Contracts\Permissions\UserGroup
     */
    public static function find($id)
    {
        return app('UserGroupsService')->id($id);
    }

    /**
     * Get a group by handle
     *
     * @param  string $handle
     * @return \Statamic\Contracts\Permissions\UserGroup
     */
    public static function whereHandle($handle)
    {
        return app('UserGroupsService')->handle($handle);
    }

    /**
     * Get the user groups for a given user
     *
     * @param string|\Statamic\Contracts\Permissions\Permissible $user
     * @return \Illuminate\Support\Collection
     */
    public static function whereUser($user)
    {
        $groups = [];

        // If a User object was provided, we'll just get the ID
        $user = ($user instanceof Permissible) ? $user->id() : $user;

        foreach (self::all() as $group_id => $group) {
            if ($group->hasUser($user)) {
                $groups[$group_id] = $group;
            }
        }

        return collect($groups);
    }
}

<?php

namespace Statamic\Addons\Users;

use Statamic\API\User;
use Statamic\Addons\Relate\RelateFieldtype;

class UsersFieldtype extends RelateFieldtype
{
    public function preProcess($data)
    {
        if ($data === 'current') {
            $user = User::getCurrent();
            $data = $user->id();
        }

        if ($this->get('max_items') === 1) {
            return $data;
        }

        return [$data];
    }
}

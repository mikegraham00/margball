<?php

namespace Statamic\CP\Publish;

use Statamic\API\User;
use Statamic\API\Config;
use Statamic\API\Helper;

class UserPublisher extends Publisher
{
    protected $login_type;

    /**
     * Prepare the content object
     *
     * Retrieve, update, and/or create an Entry, depending on the situation.
     */
    protected function prepare()
    {
        $this->login_type = Config::get('users.login_type');

        $username = array_get($this->fields, 'username');
        $email = array_get($this->fields, 'email');

        $groups   = array_get($this->fields, 'user_groups', []);

        unset($this->fields['username'], $this->fields['user_groups'], $this->fields['status']);

        if ($this->isNew()) {
            // Creating a brand new user
            $this->content = User::create()->username($username)->get();

            // Set the ID now because the $user->groups() method relies on it
            $this->id = Helper::makeUuid();
            $this->content->id($this->id);

        } else {
            // Updating an existing user
            $this->prepForExistingUser();

            $this->content->username($username);
            $this->content->email($email);
        }

        $this->content->groups($groups);
    }

    /**
     * Prepare an existing user
     *
     * @throws \Exception
     */
    private function prepForExistingUser()
    {
        $this->id = $this->request->input('uuid');

        $this->content = User::find($this->id);
    }

    /**
     * Perform initial validation
     *
     * @throws \Statamic\Exceptions\PublishException
     */
    protected function initialValidation()
    {
        //
    }
}

<?php

namespace Statamic\Stache\Drivers;

use Statamic\API\User;
use Statamic\API\YAML;
use Statamic\API\Folder;
use Statamic\Stache\Repository;

class UsersDriver extends AbstractDriver
{
    public function getFilesystemDriver()
    {
        return Folder::disk('users')->filesystem()->getDriver();
    }

    public function getFilesystemRoot()
    {
        return '/';
    }

    public function createItem($path, $contents)
    {
        $user = User::create()
            ->username(pathinfo($path)['filename'])
            ->with(YAML::parse($contents))
            ->get();

        $user->ensureSecured();

        return $user;
    }

    public function isMatchingFile($file)
    {
        return $file['type'] === 'file' && $file['extension'] === 'yaml';
    }

    public function toPersistentArray($repo)
    {
        return [
            'meta' => [
                'paths' => $repo->getPaths()->all()
            ],
            'items' => ['users' => $repo->getItems()]
        ];
    }

    /**
     * Get the locale based on the path
     *
     * @param string $path
     * @return string
     */
    public function getLocaleFromPath($path)
    {
        dd('users locale from path', $path);
    }

    /**
     * Get the localized URL
     *
     * @param        $locale
     * @param array  $data
     * @param string $path
     * @return string
     */
    public function getLocalizedUri($locale, $data, $path)
    {
        dd('users locale', $path, $data);
    }
}
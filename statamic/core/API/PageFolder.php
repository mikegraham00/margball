<?php

namespace Statamic\API;

class PageFolder
{
    /**
     * Get all page folders
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return app('PageFoldersService')->all();
    }

    /**
     * Get a page folder by handle
     *
     * @param string $handle
     * @return \Statamic\Contracts\Data\Pages\PageFolder
     */
    public static function whereHandle($handle)
    {
        return app('PageFoldersService')->handle($handle);
    }

    /**
     * Check if a page folder exists by its handle
     *
     * @param string $handle
     * @return bool
     */
    public static function handleExists($handle)
    {
        return self::whereHandle($handle) !== null;
    }

    /**
     * Create a page folder
     *
     * @return \Statamic\Contracts\Data\Pages\PageFolder
     */
    public static function create()
    {
        /** @var \Statamic\Contracts\Data\Pages\PageFolder $folder */
        $folder = app('Statamic\Contracts\Data\Pages\PageFolder');

        return $folder;
    }
}
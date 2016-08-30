<?php

namespace Statamic\API;

class Stache
{
    /**
     * @throws \Exception
     */
    public static function update()
    {
        app('stache.manager')->update();
    }

    /**
     * Clear the Stache
     *
     * @return void
     */
    public static function clear()
    {
        Folder::delete('local/cache/stache');
    }
}

<?php

namespace Statamic\Stache;

use Statamic\API\File;
use Statamic\API\Folder;

class ItemLoader
{
    /**
     * @var \Statamic\Stache\Repository
     */
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo = $repo;
    }

    public function load()
    {
        // Get all the data from the cache files. For now, we're
        // storing as files so we can see what's going on.
        $files = Folder::getFiles('local/cache/stache/'.$this->repo->cacheKey());

        $data = collect($files)->map(function ($path) {
            return unserialize(File::get($path));
        });

        // Combine all the items from the different cache files.
        return $data->flatMap(function ($collection) {
            return $collection->all();
        });
    }
}
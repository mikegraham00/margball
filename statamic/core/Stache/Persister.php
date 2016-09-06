<?php

namespace Statamic\Stache;

use Illuminate\Support\Collection;
use Statamic\API\File;

class Persister
{
    /**
     * @var \Statamic\Stache\Stache
     */
    private $stache;

    /**
     * @var Collection
     */
    private $meta;

    /**
     * @var Collection
     */
    private $items;

    /**
     * @param \Statamic\Stache\Stache $stache
     */
    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->items = collect();
    }

    /**
     * Persist the Stache to Cache
     *
     * @param Collection $updates  Repos that need to be persisted
     */
    public function persist($updates)
    {
        // Get the meta from the stache
        $this->meta = collect($this->stache->meta());

        // Loop through all the updated repos and format their data according to
        // how their driver has specified it. Put the data into arrays
        // that we can loop over in a moment.
        $updates->each(function ($key) {
            $repo = $this->stache->repo($key);

            $arr = $this->stache->driver($key)->toPersistentArray($repo);

            $this->meta->put($key, $arr['meta']);
            $this->items->put($key, $arr['items']);
        });

        // Store meta data separately. This will be simple data that can
        // be loaded all the time with minimal overhead.
        $this->store('meta', $this->meta->all());

        // Loop through all the item objects which each driver has organized
        // into folders. These are separate because it has the potential to
        // be quite large. These will be lazy loaded to prevent overhead.
        $this->items->each(function ($folders, $key) {
            collect($folders)->each(function ($data, $folder) use ($key) {
                $this->store($key . '/' . $folder, $data);
            });
        });
    }

    /**
     * Store the value
     *
     * @param string $key
     * @param mixed $value
     */
    private function store($key, $value)
    {
        $value = serialize($value);

        File::put('local/cache/stache/'.$key.'.txt', $value);
    }
}
<?php

namespace Statamic\Stache;

use Statamic\API\Config;
use Statamic\API\File;

class UpdateManager
{
    /**
     * @var \Statamic\Stache\Stache
     */
    private $stache;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $timestamps;

    /**
     * Repos that have been updated
     *
     * @var \Illuminate\Support\Collection
     */
    public $updates;

    /**
     * @param \Statamic\Stache\Stache $stache
     */
    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->timestamps = collect();

        $this->resetUpdateStatus();
    }

    /**
     * Update the Stache
     *
     * For each Stache driver, we'll do the following:
     * - Traverse their section of the filesystem
     * - Find new/modified, and deleted files
     * - Add/update, and remove their corresponding items from the Stache
     */
    public function update()
    {
        $this->stache->drivers()->each(function (Driver $driver) {
            $traverser = $this->createTraverser($driver);

            $updater = new Updater($this->stache, $driver);

            $updated = $updater
                ->modified($traverser->modified())
                ->deleted($traverser->deleted())
                ->update();

            // If any update activity occurred, we'll keep track of it.
            if ($updated) {
                $this->updates->push($driver->key());
            }

            $this->timestamps->put($driver->key(), $traverser->timestamps()->all());
        });

        $this->persistTimestamps();
        $this->persistConfig();
    }

    /**
     * Have any updates occurred?
     *
     * @return bool
     */
    public function updated()
    {
        return ! $this->updates->isEmpty();
    }

    /**
     * Get the repos that have been updated
     *
     * @return \Illuminate\Support\Collection
     */
    public function updates()
    {
        return $this->updates;
    }

    /**
     * Reset the updates state
     *
     * @return void
     */
    public function resetUpdateStatus()
    {
        $this->updates = collect();
    }

    /**
     * Create a Traverser and perform the traversal
     *
     * @param Driver $driver
     * @return \Statamic\Stache\Traverser
     */
    protected function createTraverser($driver)
    {
        $filesystem = $driver->getFilesystemDriver();

        $traverser = new Traverser($driver, $filesystem);

        $traverser->timestamps(
            $this->getTimestamps()->get($driver->key(), collect())
        );

        $traverser->traverse();

        return $traverser;
    }

    /**
     * Get the existing timestamps
     *
     * @return mixed
     */
    protected function getTimestamps()
    {
        $filename = 'local/cache/stache/timestamps.txt';

        return (File::exists($filename))
            ? collect(unserialize(File::get($filename)))
            : collect();
    }

    /**
     * Persist the timestamps for next time
     *
     * @return void
     */
    private function persistTimestamps()
    {
        File::put(
            'local/cache/stache/timestamps.txt',
            serialize($this->timestamps->all())
        );
    }

    /**
     * Persist the config for next time
     *
     * @return void
     */
    private function persistConfig()
    {
        File::put(
            'local/cache/stache/config.txt',
            serialize(Config::all())
        );
    }
}
<?php

namespace Statamic\Console\Commands\Assets;

use Statamic\API\Asset;
use Statamic\API\AssetContainer;
use Statamic\API\Assets;
use Illuminate\Console\Command;
use Statamic\API\Stache;

class AssetsSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:sync 
                            {--container : ID of the container to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the assets in a container.';

    /**
     * @var Collection
     */
    protected $containers;

    /**
     * @var bool
     */
    protected $notified_of_wait = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->containers = collect(Assets::getContainers());

        $ids = $this->containers->map(function ($container) {
            return "{$container->title()} (ID: {$container->id()})";
        })->push('All Containers')->values()->all();

        $chosen = $this->choice('Which container would you like to sync?', $ids);

        if ($chosen === 'All Containers') {
            return $this->syncAll();
        }

        preg_match('/ID: (.*)\)$/', $chosen, $matches);
        $id = $matches[1];

        $this->syncContainer($id);
    }

    private function syncAll()
    {
        $this->containers->each(function ($container) {
            $this->syncContainer($container->id());
        });
    }

    private function syncContainer($id)
    {
        $container = $this->containers->get($id);

        $before = $container->assets()->count();

        $this->line(sprintf("\n".'Syncing "%s"...', $container->title()));

        if (! $this->notified_of_wait) {
            $this->warn('Depending on the number of files, this may take a while.');
            $this->notified_of_wait = true;
        }

        $container->sync();

        Stache::update();

        $after = AssetContainer::find($id)->assets()->count() - $before;

        $this->info(sprintf('Sync complete! Added %s new assets.', $after));
    }
}

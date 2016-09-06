<?php

namespace Statamic\Addons\Search;

use Statamic\API\Cache;
use Statamic\API\Config;
use Statamic\API\Search;
use Statamic\Extend\Listener;
use Statamic\Events\StacheUpdated;

class SearchListener extends Listener
{
    public $events = [
        StacheUpdated::class => 'handle'
    ];

    public function handle(StacheUpdated $event)
    {
        // If search auto-indexing is disabled, we don't need to do anything.
        if (! Config::get('search.auto_index')) {
            return;
        }

        // Check if any content we're interested in was updated.
        // If none was, then we don't care. Don't do anything.
        if (! $event->updatedAny(['pages', 'entries', 'terms'])) {
            return;
        }

        // If we're inside the idle period, don't do anything.
        if (Cache::get('search_index_idle', false)) {
            return;
        }

        // Made it this far, congrats. Your prize is a fresh search index.
        Search::update();

        // Begin the idle period which will last for a configurable amount of time.
        // During the idle period, automated search index will be disabled.
        Cache::put('search_index_idle', true, Config::get('search.index_frequency'));
    }
}

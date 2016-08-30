<?php

namespace Statamic\Stache\Listeners;

use Statamic\API\Str;
use Statamic\Contracts\Data\Entries\Entry;
use Statamic\Contracts\Data\Globals\GlobalSet;
use Statamic\Contracts\Data\Pages\Page;
use Statamic\Contracts\Data\Taxonomies\Term;
use Statamic\Stache\Stache;
use Statamic\Contracts\Data\Content\Content;

class UpdateContent
{
    /**
     * @var Stache
     */
    protected $stache;

    /**
     * @var Content
     */
    protected $content;

    /**
     * Create a new listener
     *
     * @param Stache $stache
     */
    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
    }

    /**
     * Handle the event.
     *
     * @param Content $content
     * @return void
     */
    public function handle(Content $content)
    {
        $this->content = $content;

        if (! $repo = $this->repo()) {
            \Log::error("Uncaught content type encountered when updating the Stache.");
            return;
        }

        $id = $content->id();

        $this->stache->repo($repo)
            ->load()
            ->setItem($id, $content)
            ->setPath($id, $content->path());

        if ($this->stache->driver($this->driverKey($repo))->isRoutable()) {
            $this->stache->repo($repo)->setUri($id, $content->uri());
        }

        $this->stache->updated($this->repo());
    }

    /**
     * Get the appropriate Stache repo key
     *
     * @return string
     */
    protected function repo()
    {
        if ($this->content instanceof Page) {
            return 'pages';
        } elseif ($this->content instanceof Entry) {
            return 'entries::' . $this->content->collectionName();
        } elseif ($this->content instanceof Term) {
            return 'terms::' . $this->content->taxonomyName();
        } elseif ($this->content instanceof GlobalSet) {
            return 'globals';
        }
    }

    /**
     * Get the driver key
     *
     * @param string $key
     * @return string
     */
    protected function driverKey($key)
    {
        return (Str::contains($key, '::')) ? explode('::', $key)[0] : $key;
    }
}
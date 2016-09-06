<?php

namespace Statamic\Stache\Drivers;

use Statamic\API\Term;

class TermsDriver extends AbstractDriver implements AggregateDriver
{
    protected $localizable = true;
    protected $routable = true;

    public function getFilesystemRoot()
    {
        return 'taxonomies';
    }

    public function getModifiedItems($files)
    {
        $creator = new TermItemCreator(
            $this->stache,
            $files
        );

        return $creator->create();
    }

    public function createItem($path, $contents)
    {
        //
    }

    /**
     * Delete the items from the repo
     *
     * @param \Statamic\Stache\Repository $repo
     * @param \Illuminate\Support\Collection $deleted
     * @param \Illuminate\Support\Collection $modified
     */
    public function deleteItems($repo, $deleted, $modified)
    {
        $deleted->each(function ($path) use ($repo) {
            $key = $this->getKeyFromPath($path);
            $id = $repo->getIdByPath("$key::$path");
            $repo->removeItem("$key::$id");
        });
    }

    public function isMatchingFile($file)
    {
        return $file['type'] === 'file' && $file['basename'] !== 'folder.yaml';
    }

    /**
     * Get the locale based on the path
     *
     * @param string $path
     * @return string
     */
    public function getLocaleFromPath($path)
    {
        $parts = explode('/', $path);

        if (count($parts) === 3) {
            return default_locale();
        }

        return $parts[2];
    }

    /**
     * Get the key from a path
     *
     * @param string $path
     * @return string
     */
    public function getKeyFromPath($path)
    {
        // Get the taxonomy
        return explode('/', $path)[1];
    }

    public function toPersistentArray($repo)
    {
        return [
            'meta' => [
                'paths' => $this->getPersistentPaths($repo),
                'uris' => $this->getPersistentUris($repo)
            ],
            'items' => $this->getPersistentItems($repo)
        ];
    }

    private function getPersistentPaths($repo)
    {
        $all_paths = [];

        foreach ($repo->getPathsForAllLocales()->toArray() as $taxonomy => $locales) {
            foreach ($locales as $locale => $paths) {
                foreach ($paths as $id => $path) {
                    array_set($all_paths, $locale . '.' . $taxonomy . '::' . $id, $path);
                }
            }
        }

        return $all_paths;
    }

    private function getPersistentUris($repo)
    {
        $all_uris = [];

        foreach ($repo->getUrisForAllLocales()->toArray() as $taxonomy => $locales) {
            foreach ($locales as $locale => $uris) {
                foreach ($uris as $id => $path) {
                    array_set($all_uris, $locale . '.' . $taxonomy . '::' . $id, $path);
                }
            }
        }

        return $all_uris;
    }

    private function getPersistentItems($repo)
    {
        $items = [];

        foreach ($repo->getItems() as $key => $collection) {
            $items[$key.'/items'] = $collection;
        }

        return $items;
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
        return Term::find($data['id'])->in($locale)->uri();
    }
}
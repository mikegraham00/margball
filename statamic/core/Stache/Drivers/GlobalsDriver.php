<?php

namespace Statamic\Stache\Drivers;

use Statamic\API\GlobalSet;
use Statamic\API\YAML;

class GlobalsDriver extends AbstractDriver
{
    /**
     * @var bool
     */
    protected $localizable = true;

    public function getFilesystemRoot()
    {
        return 'globals';
    }

    public function createItem($path, $contents)
    {
        return GlobalSet::create(pathinfo($path)['filename'])
            ->with(YAML::parse($contents))
            ->get();
    }

    public function isMatchingFile($file)
    {
        return $file['type'] === 'file' && $file['extension'] === 'yaml';
    }

    public function toPersistentArray($repo)
    {
        return [
            'meta' => [
                'paths' => $repo->getPathsForAllLocales()->toArray()
            ],
            'items' => ['globals' => $repo->getItems()]
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
        $parts = explode('/', $path);

        if (count($parts) === 2) {
            return default_locale();
        }

        return $parts[1];
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
        //
    }
}
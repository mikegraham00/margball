<?php

namespace Statamic\Stache\Drivers;

use Statamic\API\AssetContainer;
use Statamic\API\Folder;
use Statamic\API\YAML;

class AssetContainersDriver extends AbstractDriver
{
    protected $relatable = false;

    public function getFilesystemDriver()
    {
        return Folder::disk('storage')->filesystem()->getDriver();
    }

    public function getFilesystemRoot()
    {
        return 'assets';
    }

    public function createItem($path, $contents)
    {
        $data = YAML::parse($contents);

        $container = AssetContainer::create();
        $container->id(explode('/', $path)[1]);
        $container->driver(array_get($data, 'driver', 'local'));
        $container->path(array_get($data, 'path'));
        $container->title(array_get($data, 'title'));
        $container->fieldset(array_get($data, 'fieldset'));

        return $container;
    }

    public function isMatchingFile($file)
    {
        return $file['basename'] === 'container.yaml';
    }

    public function toPersistentArray($repo)
    {
        return [
            'meta' => [
                'paths' => $repo->getPaths()->all(),
                'uris' => $repo->getUris()->all(),
            ],
            'items' => ['item' => $repo->getItems()]
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
        dd('asset container locale from path', $path);
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
        dd('asset container locale', $path, $data);
    }
}
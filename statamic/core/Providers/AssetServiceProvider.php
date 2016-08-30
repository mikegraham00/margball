<?php

namespace Statamic\Providers;

use Statamic\API\Config;
use League\Glide\ServerFactory;
use Statamic\Assets\AssetContainer;
use Statamic\Assets\AssetFactory;
use Statamic\Imaging\GlideUrlBuilder;
use Illuminate\Support\ServiceProvider;
use Statamic\Assets\AssetContainerFactory;
use League\Glide\Responses\LaravelResponseFactory;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Statamic\Contracts\Assets\AssetContainer', function() {
            return new AssetContainer;
        });

        $this->app->bind('Statamic\Contracts\Assets\AssetFactory', function () {
            return new AssetFactory;
        });

        $this->app->bind('Statamic\Contracts\Assets\AssetContainerFactory', function() {
            return new AssetContainerFactory;
        });

        $this->app->bind('Statamic\Contracts\Imaging\UrlBuilder', function() {
            return new GlideUrlBuilder;
        });

        $this->app->singleton('League\Glide\Server', function() {
            return ServerFactory::create([
                'source'   => path(STATAMIC_ROOT), // this gets overriden in GlideController when using assets
                'cache'    => cache_path('glide'),
                'base_url' => Config::get('assets.image_manipulation_route', 'img'),
                'response' => new LaravelResponseFactory(app('request')),
                'driver'   => Config::get('assets.image_manipulation_driver')
            ]);
        });
    }
}

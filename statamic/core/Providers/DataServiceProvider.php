<?php

namespace Statamic\Providers;

use Illuminate\Support\ServiceProvider;
use Statamic\Data\Content\OrderParser;
use Statamic\Data\Content\PathBuilder;
use Statamic\Data\Content\StatusParser;
use Statamic\Data\Content\UrlBuilder;
use Statamic\Data\Entries\Collection;
use Statamic\Data\Entries\EntryFactory;
use Statamic\Data\Globals\GlobalFactory;
use Statamic\Data\Pages\PageFactory;
use Statamic\Data\Pages\PageFolder;
use Statamic\Data\Pages\PageTreeReorderer;
use Statamic\Data\Services\PageStructureService;
use Statamic\Data\Services\UserGroupsService;
use Statamic\Data\Taxonomies\TermFactory;
use Statamic\Data\Taxonomies\Taxonomy;
use Statamic\Data\Services\AssetContainersService;
use Statamic\Data\Services\AssetFoldersService;
use Statamic\Data\Services\AssetsService;
use Statamic\Data\Services\CollectionsService;
use Statamic\Data\Services\ContentService;
use Statamic\Data\Services\EntriesService;
use Statamic\Data\Services\GlobalsService;
use Statamic\Data\Services\PageFoldersService;
use Statamic\Data\Services\PagesService;
use Statamic\Data\Services\TaxonomiesService;
use Statamic\Data\Services\TermsService;
use Statamic\Data\Services\UsersService;

class DataServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Statamic\Contracts\Data\Content\OrderParser', function() {
            return new OrderParser;
        });

        $this->app->bind('Statamic\Contracts\Data\Content\StatusParser', function() {
            return new StatusParser;
        });

        $this->app->bind('Statamic\Contracts\Data\Content\PathBuilder', function() {
            return new PathBuilder;
        });

        $this->app->bind('Statamic\Contracts\Data\Content\UrlBuilder', function() {
            return new UrlBuilder;
        });

        $this->app->bind('PagesService', function () {
            return new PagesService($this->app->make('stache'));
        });

        $this->app->bind('EntriesService', function () {
            return new EntriesService($this->app->make('stache'));
        });

        $this->app->bind('CollectionsService', function () {
            return new CollectionsService($this->app->make('stache'));
        });

        $this->app->bind('TermsService', function () {
            return new TermsService($this->app->make('stache'));
        });

        $this->app->bind('TaxonomiesService', function () {
            return new TaxonomiesService($this->app->make('stache'));
        });

        $this->app->bind('PageFoldersService', function () {
            return new PageFoldersService($this->app->make('stache'));
        });

        $this->app->bind('PageStructureService', function () {
            return new PageStructureService($this->app->make('stache'));
        });

        $this->app->bind('GlobalsService', function () {
            return new GlobalsService($this->app->make('stache'));
        });

        $this->app->bind('ContentService', function () {
            return new ContentService($this->app->make('stache'));
        });

        $this->app->bind('UsersService', function () {
            return new UsersService($this->app->make('stache'));
        });

        $this->app->bind('UserGroupsService', function () {
            return new UserGroupsService($this->app->make('stache'));
        });

        $this->app->bind('AssetsService', function () {
            return new AssetsService($this->app->make('stache'));
        });

        $this->app->bind('AssetContainersService', function () {
            return new AssetContainersService($this->app->make('stache'));
        });

        $this->app->bind('AssetFoldersService', function () {
            return new AssetFoldersService($this->app->make('stache'));
        });

        $this->app->bind('Statamic\Contracts\Data\Pages\PageFactory', function() {
            return new PageFactory;
        });

        $this->app->bind('Statamic\Contracts\Data\Pages\PageFolder', function() {
            return new PageFolder;
        });

        $this->app->bind('Statamic\Contracts\Data\Pages\PageTreeReorderer', function() {
            return new PageTreeReorderer;
        });
        $this->app->bind('Statamic\Contracts\Data\Entries\EntryFactory', function() {
            return new EntryFactory;
        });

        $this->app->bind('Statamic\Contracts\Data\Entries\Collection', function() {
            return new Collection;
        });

        $this->app->bind('Statamic\Contracts\Data\Taxonomies\TermFactory', function() {
            return new TermFactory;
        });

        $this->app->bind('Statamic\Contracts\Data\Taxonomies\Taxonomy', function() {
            return new Taxonomy;
        });

        $this->app->bind('Statamic\Contracts\Data\Globals\GlobalFactory', function() {
            return new GlobalFactory;
        });
    }
}

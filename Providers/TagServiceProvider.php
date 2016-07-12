<?php

namespace Modules\Tag\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Tag\Entities\Tag;
use Modules\Tag\Repositories\Cache\CacheTagDecorator;
use Modules\Tag\Repositories\Eloquent\EloquentTagRepository;
use Modules\Tag\Repositories\TagManager;
use Modules\Tag\Repositories\TagManagerRepository;
use Modules\Tag\Repositories\TagRepository;

class TagServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    public function boot()
    {
        $this->publishConfig('tag', 'permissions');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(TagRepository::class, function () {
            $repository = new EloquentTagRepository(new Tag());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheTagDecorator($repository);
        });

        $this->app->singleton(TagManager::class, function () {
            return new TagManagerRepository();
        });
    }
}

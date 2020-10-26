<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Common\Tree;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadViewsFrom(public_path().'/themes/vender/filer', 'filer');
        $this->loadViewsFrom(public_path().'/themes/vender/widgets', 'widgets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tree',function(){
            return new Tree;
        });
        $this->app->bind(
            'App\Repositories\Eloquent\PageRepositoryInterface',
            \App\Repositories\Eloquent\PageRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\PageCategoryRepositoryInterface',
            \App\Repositories\Eloquent\PageCategoryRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\PageRecruitRepositoryInterface',
            \App\Repositories\Eloquent\PageRecruitRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\SettingRepositoryInterface',
            \App\Repositories\Eloquent\SettingRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\BannerRepositoryInterface',
            \App\Repositories\Eloquent\BannerRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\LinkRepositoryInterface',
            \App\Repositories\Eloquent\LinkRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\NavRepositoryInterface',
            \App\Repositories\Eloquent\NavRepository::class
        );
        $this->app->bind(
            'App\Repositories\Eloquent\NavCategoryRepositoryInterface',
            \App\Repositories\Eloquent\NavCategoryRepository::class
        );
        $this->app->bind('filer', function ($app) {
            return new \App\Helpers\Filer\Filer();
        });
        $this->app->singleton('image', function ($app) {
            return new ImageManager($app['config']->get('image'));
        });
        $this->app->bind('message', function ($app) {
            return new \App\Repositories\Eloquent\MessageRepository($app);
        });
        $this->app->bind('continent', function ($app) {
            return new \App\Repositories\Eloquent\ContinentRepository($app);
        });
        $this->app->bind('airport_type_repository', function ($app) {
            return new \App\Repositories\Eloquent\AirportTypeRepository($app);
        });
        $this->app->bind('excel_service', function ($app) {
            return new \App\Services\ExcelService($app->request);
        });
    }

    public function provides()
    {

    }
}

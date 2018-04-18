<?php

namespace Foostart\Follower;

use Illuminate\Support\ServiceProvider;
use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;

use URL, Route;
use Illuminate\Http\Request;


class FollowerServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request) {
        /**
         * Publish
         */
         $this->publishes([
            __DIR__.'/config/follower_admin.php' => config_path('follower_admin.php'),
        ],'config');

        $this->loadViewsFrom(__DIR__ . '/views', 'follower');


        /**
         * Translations
         */
         $this->loadTranslationsFrom(__DIR__.'/lang', 'follower');


        /**
         * Load view composer
         */
        $this->followerViewComposer($request);

         $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        include __DIR__ . '/routes.php';

        /**
         * Load controllers
         */
        $this->app->make('Foostart\Follower\Controllers\Admin\FollowerAdminController');

         /**
         * Load Views
         */
        $this->loadViewsFrom(__DIR__ . '/views', 'follower');
    }

    /**
     *
     */
    public function followerViewComposer(Request $request) {

        view()->composer('follower::follower*', function ($view) {
            global $request;
            $follower_id = $request->get('id');
            $is_action = empty($follower_id)?'page_add':'page_edit';

            $view->with('sidebar_items', [

                /**
                 * followers
                 */
                //list
                trans('follower::follower_admin.page_list') => [
                    'url' => URL::route('admin_follower'),
                    "icon" => '<i class="fa fa-users"></i>'
                ],
                //add
                trans('follower::follower_admin.'.$is_action) => [
                    'url' => URL::route('admin_follower.edit'),
                    "icon" => '<i class="fa fa-users"></i>'
                ],

                /**
                 * Categories
                 */
                //list
                trans('follower::follower_admin.page_category_list') => [
                    'url' => URL::route('admin_follower_category'),
                    "icon" => '<i class="fa fa-users"></i>'
                ],
            ]);
            //
        });
    }

}

<?php

use Illuminate\Session\TokenMismatchException;

/**
 * FRONT
 */
Route::get('follower', [
    'as' => 'follower',
    'uses' => 'Foostart\Follower\Controllers\Front\FollowerFrontController@index'
]);


/**
 * ADMINISTRATOR
 */
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['admin_logged', 'can_see',],
                  'namespace' => 'Foostart\Follower\Controllers\Admin',
        ], function () {

        /*
          |-----------------------------------------------------------------------
          | Manage follower
          |-----------------------------------------------------------------------
          | 1. List of followers
          | 2. Edit follower
          | 3. Delete follower
          | 4. Add new follower
          | 5. Manage configurations
          | 6. Manage languages
          |
        */

        /**
         * list
         */
        Route::get('admin/followers/list', [
            'as' => 'followers.list',
            'uses' => 'FollowerAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/followers/edit', [
            'as' => 'followers.edit',
            'uses' => 'FollowerAdminController@edit'
        ]);

        /**
         * copy
         */
        Route::get('admin/followers/copy', [
            'as' => 'followers.copy',
            'uses' => 'FollowerAdminController@copy'
        ]);

        /**
         * post
         */
        Route::post('admin/followers/edit', [
            'as' => 'followers.post',
            'uses' => 'FollowerAdminController@post'
        ]);

        /**
         * delete
         */
        Route::get('admin/followers/delete', [
            'as' => 'followers.delete',
            'uses' => 'FollowerAdminController@delete'
        ]);

        /**
         * trash
         */
         Route::get('admin/followers/trash', [
            'as' => 'followers.trash',
            'uses' => 'FollowerAdminController@trash'
        ]);

        /**
         * configs
        */
        Route::get('admin/followers/config', [
            'as' => 'followers.config',
            'uses' => 'FollowerAdminController@config'
        ]);

        Route::post('admin/followers/config', [
            'as' => 'followers.config',
            'uses' => 'FollowerAdminController@config'
        ]);

        /**
         * language
        */
        Route::get('admin/followers/lang', [
            'as' => 'followers.lang',
            'uses' => 'FollowerAdminController@lang'
        ]);

        Route::post('admin/followers/lang', [
            'as' => 'followers.lang',
            'uses' => 'FollowerAdminController@lang'
        ]);


        Route::get('admin/followers/add', [
            'as' => 'followers.add',
            'uses' => 'FollowerAdminController@add'
        ]);
        /**
         * post
         */
        Route::post('admin/followers/postadd', [
            'as' => 'followers.postadd',
            'uses' => 'FollowerAdminController@postAdd'
        ]);

    });
});
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['can_see',],
                  'namespace' => 'Foostart\Follower\Controllers\User',
    ], function () {
        Route::get('user/followers/sample', [
            'as' => 'followers.sample',
            'uses' => 'FollowerUserController@index'
        ]);
        Route::get('user/followers/userAdd', [
            'as' => 'followers.userAdd',
            'uses' => 'FollowerUserController@userAdd'
        ]);
        Route::post('user/followers/userPostAdd', [
            'as' => 'followers.userPostAdd',
            'uses' => 'FollowerUserController@userPostAdd'
        ]);
    });

});
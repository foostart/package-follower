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

    Route::group(['middleware' => ['admin_logged', 'can_see']], function () {

        ////////////////////////////////////////////////////////////////////////
        ////////////////////////////FollowerS ROUTE///////////////////////////////
        ////////////////////////////////////////////////////////////////////////
        /**
         * list
         */
        Route::get('admin/follower/list', [
            'as' => 'admin_follower',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/follower/edit', [
            'as' => 'admin_follower.edit',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerAdminController@edit'
        ]);

        /**
         * post
         */
        Route::post('admin/follower/edit', [
            'as' => 'admin_follower.post',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerAdminController@post'
        ]);

        /**
         * delete
         */
        Route::get('admin/follower/delete', [
            'as' => 'admin_follower.delete',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerAdminController@delete'
        ]);
        ////////////////////////////////////////////////////////////////////////
        ////////////////////////////FollowerS ROUTE///////////////////////////////
        ////////////////////////////////////////////////////////////////////////




        
        ////////////////////////////////////////////////////////////////////////
        ////////////////////////////CATEGORIES///////////////////////////////
        ////////////////////////////////////////////////////////////////////////
         Route::get('admin/follower_category', [
            'as' => 'admin_follower_category',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerCategoryAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/follower_category/edit', [
            'as' => 'admin_follower_category.edit',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerCategoryAdminController@edit'
        ]);

        /**
         * post
         */
        Route::post('admin/follower_category/edit', [
            'as' => 'admin_follower_category.post',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerCategoryAdminController@post'
        ]);
         /**
         * delete
         */
        Route::get('admin/follower_category/delete', [
            'as' => 'admin_follower_category.delete',
            'uses' => 'Foostart\Follower\Controllers\Admin\FollowerCategoryAdminController@delete'
        ]);
        ////////////////////////////////////////////////////////////////////////
        ////////////////////////////CATEGORIES///////////////////////////////
        ////////////////////////////////////////////////////////////////////////
    });
});

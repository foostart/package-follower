<?php

use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;
use Foostart\Category\Helpers\FooCategory;

/*
|-----------------------------------------------------------------------
| GLOBAL VARIABLES
|-----------------------------------------------------------------------
|   $sidebar_items
|   $sorting
|   $order_by
|   $plang_admin = 'follower-admin'
|   $plang_front = 'follower-front'
*/
View::composer([
                'package-follower::admin.follower-edit',
                'package-follower::admin.follower-form',
                'package-follower::admin.follower-items',
                'package-follower::admin.follower-item',
                'package-follower::admin.follower-search',
                'package-follower::admin.follower-config',
                'package-follower::admin.follower-lang',
                'package-follower::admin.follower-user-table',

    ], function ($view) {

        /**
         * $plang-admin
         * $plang-front
         */
        $plang_admin = 'follower-admin';
        $plang_front = 'follower-front';

        $view->with('plang_admin', $plang_admin);
        $view->with('plang_front', $plang_front);

        $fooCategory = new FooCategory();
        $key = $fooCategory->getContextKeyByRef('admin/followers');
        /**
         * $sidebar_items
         */
        $view->with('sidebar_items', [
            trans('follower-admin.sidebar.addfollower') => [
                'url' => URL::route('followers.add', []),
                'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
            ],
            trans('follower-admin.sidebar.list') => [
                "url" => URL::route('followers.list', []),
                'icon' => '<i class="fa fa-list-ul" aria-hidden="true"></i>'
            ],
            // trans('follower-admin.sidebar.category') => [
            //     'url'  => URL::route('categories.list',['_key='.$key]),
            //     'icon' => '<i class="fa fa-sitemap" aria-hidden="true"></i>'
            // ],
            trans('follower-admin.sidebar.config') => [
                "url" => URL::route('followers.config', []),
                'icon' => '<i class="fa fa-braille" aria-hidden="true"></i>'
            ],
            trans('follower-admin.sidebar.lang') => [
                "url" => URL::route('followers.lang', []),
                'icon' => '<i class="fa fa-language" aria-hidden="true"></i>'
            ],
            trans('follower-admin.sidebar.sample') => [
                "url" => URL::route('followers.sample', []),
                'icon' => '<i class="fa fa-plus-circle" aria-hidden="true"></i>'
            ],
        ]);

        /**
         * $sorting
         * $order_by
         */
        $orders = [
            '' => trans($plang_admin.'.form.no-selected'),
            'id' => trans($plang_admin.'.fields.id'),
            'user_following_name' => trans($plang_admin.'.fields.follow_name'),
            'user_following_email' => trans($plang_admin.'.fields.follow_email'),
            'created_at' => trans($plang_admin.'.fields.created_at'),
            'updated_at' => trans($plang_admin.'.fields.updated_at'),
            'email' => trans($plang_admin.'.fields.email'),
            'first_name' => trans($plang_admin.'.fields.name'),
            'last_name' => trans($plang_admin.'.fields.created_at'),
            'last_login' => trans($plang_admin.'.fields.updated_at'),
            'follower_status' => trans($plang_admin.'.fields.follower_status'),
        ];
        $sorting = [
            'label' => $orders,
            'items' => [],
            'url' => []
        ];
        //Order by params
        $params = Request::all();

        $order_by = explode(',', @$params['order_by']);
        $ordering = explode(',', @$params['ordering']);
        foreach ($orders as $key => $value) {
            $_order_by = $order_by;
            $_ordering = $ordering;
            if (!empty($key)) {
                //existing key in order
                if (in_array($key, $order_by)) {
                    $index = array_search($key, $order_by);
                    switch ($_ordering[$index]) {
                        case 'asc':
                            $sorting['items'][$key] = 'asc';
                            $_ordering[$index] = 'desc';
                            break;
                        case 'desc':
                             $sorting['items'][$key] = 'desc';
                            $_ordering[$index] = 'asc';
                            break;
                        default:
                            break;
                    }
                    $order_by_str = implode(',', $_order_by);
                    $ordering_str = implode(',', $_ordering);

                } else {//new key in order
                    $sorting['items'][$key] = 'none';//asc
                    if (empty($params['order_by'])) {
                        $order_by_str = $key;
                        $ordering_str = 'asc';
                    } else {
                        $_order_by[] = $key;
                        $_ordering[] = 'asc';
                        $order_by_str = implode(',', $_order_by);
                        $ordering_str = implode(',', $_ordering);
                    }
                }
                $sorting['url'][$key]['order_by'] = $order_by_str;
                $sorting['url'][$key]['ordering'] = $ordering_str;
            }
        }
        foreach ($sorting['url'] as $key => $item) {
            $params['order_by'] = $item['order_by'];
            $params['ordering'] = $item['ordering'];
            $sorting['url'][$key] = Request::url().'?'.http_build_query($params);
        }
        $view->with('sorting', $sorting);

        //Order by
        $order_by = [
            'asc' => trans('foostart.order_by.asc'),
            'desc' => trans('foostart.order_by.desc'),
        ];
        $view->with('order_by', $order_by);
});



/* USER MENU
|-----------------------------------------------------------------------
| GLOBAL VARIABLES
|-----------------------------------------------------------------------
|   $sidebar_items
|   $sorting
|   $order_by
|   $plang_admin = 'follower-admin'
|   $plang_front = 'follower-front'
*/
View::composer([
                'package-follower::user.follower-edit',
                'package-follower::user.follower-form',
                'package-follower::user.follower-items',
                'package-follower::user.follower-item',
                'package-follower::user.follower-search',
                'package-follower::user.follower-user-table',

    ], function ($view) {

        /**
         * $plang-admin
         * $plang-front
         */
        $plang_admin = 'follower-admin';
        $plang_user = 'follower-user';

        $view->with('plang_admin', $plang_admin);
        $view->with('plang_user', $plang_user);

        /**
         * $sidebar_items
         */
        $view->with('sidebar_items', [
            trans('follower-user.sidebar.addfollower') => [
                'url' => URL::route('followers.userAdd', []),
                'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
            ],
            trans('follower-user.sidebar.sample') => [
                "url" => URL::route('followers.sample', []),
                'icon' => '<i class="fa fa-plus-circle" aria-hidden="true"></i>'
            ],
        ]);

        /**
         * $sorting
         * $order_by
         */
        $orders = [
            '' => trans($plang_user.'.form.no-selected'),
            'id' => trans($plang_user.'.fields.id'),
            'user_following_name' => trans($plang_user.'.fields.follow_name'),
            'user_following_email' => trans($plang_user.'.fields.follow_email'),
            'created_at' => trans($plang_user.'.fields.created_at'),
            'updated_at' => trans($plang_user.'.fields.updated_at'),
            'email' => trans($plang_user.'.fields.email'),
            'first_name' => trans($plang_user.'.fields.name'),
            'last_name' => trans($plang_user.'.fields.created_at'),
            'last_login' => trans($plang_user.'.fields.updated_at'),
            'follower_status' => trans($plang_user.'.fields.follower_status'),
        ];
        $sorting = [
            'label' => $orders,
            'items' => [],
            'url' => []
        ];
        //Order by params
        $params = Request::all();

        $order_by = explode(',', @$params['order_by']);
        $ordering = explode(',', @$params['ordering']);
        foreach ($orders as $key => $value) {
            $_order_by = $order_by;
            $_ordering = $ordering;
            if (!empty($key)) {
                //existing key in order
                if (in_array($key, $order_by)) {
                    $index = array_search($key, $order_by);
                    switch ($_ordering[$index]) {
                        case 'asc':
                            $sorting['items'][$key] = 'asc';
                            $_ordering[$index] = 'desc';
                            break;
                        case 'desc':
                             $sorting['items'][$key] = 'desc';
                            $_ordering[$index] = 'asc';
                            break;
                        default:
                            break;
                    }
                    $order_by_str = implode(',', $_order_by);
                    $ordering_str = implode(',', $_ordering);

                } else {//new key in order
                    $sorting['items'][$key] = 'none';//asc
                    if (empty($params['order_by'])) {
                        $order_by_str = $key;
                        $ordering_str = 'asc';
                    } else {
                        $_order_by[] = $key;
                        $_ordering[] = 'asc';
                        $order_by_str = implode(',', $_order_by);
                        $ordering_str = implode(',', $_ordering);
                    }
                }
                $sorting['url'][$key]['order_by'] = $order_by_str;
                $sorting['url'][$key]['ordering'] = $ordering_str;
            }
        }
        foreach ($sorting['url'] as $key => $item) {
            $params['order_by'] = $item['order_by'];
            $params['ordering'] = $item['ordering'];
            $sorting['url'][$key] = Request::url().'?'.http_build_query($params);
        }
        $view->with('sorting', $sorting);

        //Order by
        $order_by = [
            'asc' => trans('foostart.order_by.asc'),
            'desc' => trans('foostart.order_by.desc'),
        ];
        $view->with('order_by', $order_by);
});

<?php namespace Foostart\Follower\Controllers\Admin;

/*
|-----------------------------------------------------------------------
| FollowerAdminController
|-----------------------------------------------------------------------
| @author: Kang
| @website: http://foostart.com
| @date: 28/12/2017
|
*/


use Illuminate\Http\Request;
use URL, Route, Redirect;
use Illuminate\Support\Facades\App;
use LaravelAcl\Authentication\Validators\UserValidator;
use Foostart\Category\Library\Controllers\FooController;
use Foostart\Follower\Models\Follower;
use Foostart\Category\Models\Category;
use Foostart\Follower\Validators\FollowerValidator;
use Foostart\Follower\Repository\FollowRepository;



class FollowerAdminController extends FooController {

    public $obj_item = NULL;
    public $obj_category = NULL;
    protected $user_repository;
    protected $follow_repository;
    protected $user_validator;
    public function __construct() {

        parent::__construct();
        // models
        $this->obj_item = new Follower(array('perPage' => 10));
        $this->obj_category = new Category();
        $this->user_repository = App::make('user_repository');
        $this->follow_repository = new FollowRepository();
        // validators
        $this->obj_validator = new FollowerValidator();
        $this->user_validator = new UserValidator();
        // set language files
        $this->plang_admin = 'follower-admin';
        $this->plang_front = 'follower-front';

        // package name
        $this->package_name = 'package-follower';
        $this->package_base_name = 'follower';

        // root routers
        $this->root_router = 'followers';

        // page views
        $this->page_views = [
            'admin' => [
                'items' => $this->package_name.'::admin.'.$this->package_base_name.'-items',
                'edit'  => $this->package_name.'::admin.'.$this->package_base_name.'-edit',
                'config'  => $this->package_name.'::admin.'.$this->package_base_name.'-config',
                'lang'  => $this->package_name.'::admin.'.$this->package_base_name.'-lang',
            ]
        ];

        $this->data_view['status'] = $this->obj_item->getPluckStatus();

        // //set category
        $this->category_ref_name = 'admin/followers';
        $this->statuses = config('package-follower.status.list');
    }

    /**
     * Show list of items
     * @return view list of items
     * @date 27/12/2017
     */
    public function index(Request $request) {

        $params = $request->all();

        $items = $this->obj_item->selectItems($params);

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'items' => $items,
            'request' => $request,
            'params' => $params,
            'statuses' => $this->statuses,
        ));

        return view($this->page_views['admin']['items'], $this->data_view);
    }

    /**
     * Delete existing item
     * @return view list of items
     * @date 27/12/2017
     */
    public function delete(Request $request) {

        $item = NULL;
        $flag = TRUE;
        $params = array_merge($request->all(), $this->getUser());
        $id = (int)$request->get('id');
        $ids = $request->get('ids');
        $is_valid_request = $this->isValidRequest($request);

        if ($is_valid_request && (!empty($id) || !empty($ids))) {

            $ids = !empty($id)?[$id]:$ids;

            foreach ($ids as $id) {

                $params['id'] = $id;

                if (!$this->obj_item->unFollow($params)) {
                    $flag = FALSE;
                }
            }
            if ($flag) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.unfollow-ok'));
            }
        }

        return Redirect::route($this->root_router.'.list')
                        ->withMessage(trans($this->plang_admin.'.actions.unfollow-error'));
    }

    /**
     * Manage configuration of package
     * @param Request $request
     * @return view config page
     */
    public function config(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $config_path = realpath(base_path('config/package-follower.php'));
        $package_path = realpath(base_path('vendor/foostart/package-follower'));

        $config_bakup = realpath($package_path.'/storage/backup/config');

        if ($version = $request->get('v')) {
            //load backup config
            $content = file_get_contents(base64_decode($version));
        } else {
            //load current config
            $content = file_get_contents($config_path);
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            file_put_contents($config_bakup.'/package-follower-'.date('YmdHis',time()).'.php', $content);

            //update new config
            $content = $request->get('content');

            file_put_contents($config_path, $content);
        }

        $backups = array_reverse(glob($config_bakup.'/*'));

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'content' => $content,
            'backups' => $backups,
        ));

        return view($this->page_views['admin']['config'], $this->data_view);
    }


    /**
     * Manage languages of package
     * @param Request $request
     * @return view lang page
     */
    public function lang(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $langs = config('package-follower.langs');
        $lang_paths = [];

        if (!empty($langs) && is_array($langs)) {
            foreach ($langs as $key => $value) {
                $lang_paths[$key] = realpath(base_path('resources/lang/'.$key.'/follower-admin.php'));
            }
        }

        $package_path = realpath(base_path('vendor/foostart/package-follower'));

        $lang_bakup = realpath($package_path.'/storage/backup/lang');
        $lang = $request->get('lang')?$request->get('lang'):'en';
        $lang_contents = [];

        if ($version = $request->get('v')) {
            //load backup lang
            $group_backups = base64_decode($version);
            $group_backups = empty($group_backups)?[]: explode(';', $group_backups);

            foreach ($group_backups as $group_backup) {
                $_backup = explode('=', $group_backup);
                $lang_contents[$_backup[0]] = file_get_contents($_backup[1]);
            }

        } else {
            //load current lang
            foreach ($lang_paths as $key => $lang_path) {
                $lang_contents[$key] = file_get_contents($lang_path);
            }
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            foreach ($lang_paths as $key => $value) {
                $content = file_get_contents($value);

                //format file name follower-admin-YmdHis.php
                file_put_contents($lang_bakup.'/'.$key.'/follower-admin-'.date('YmdHis',time()).'.php', $content);
            }


            //update new lang
            foreach ($langs as $key => $value) {
                $content = $request->get($key);
                file_put_contents($lang_paths[$key], $content);
            }

        }

        //get list of backup langs
        $backups = [];
        foreach ($langs as $key => $value) {
            $backups[$key] = array_reverse(glob($lang_bakup.'/'.$key.'/*'));
        }

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'backups' => $backups,
            'langs'   => $langs,
            'lang_contents' => $lang_contents,
            'lang' => $lang,
        ));

        return view($this->page_views['admin']['lang'], $this->data_view);
    }


    /**
     * Processing data from POST method: add new item, edit existing item
     * @return view edit page
     * @date 27/12/2017
     */


    public function add(Request $request) {
        $user_leader = $this->user_repository->isLeader();
        $users = $this->follow_repository->getlistUser($request);

        $this->data_view = array_merge($this->data_view, array(
            "users" => $users, 
            "request" => $request, 
            'statuses' => $this->statuses,
        ));
        return view($this->page_views['admin']['edit'], $this->data_view);
    }

    /**
     * Processing data from POST method: add new item, edit existing item
     * @return view edit page
     * @date 27/12/2017
     */
    public function postAdd(Request $request) {

        $item = NULL;

        $params = array_merge($request->all(), $this->getUser());
        $is_valid_request = $this->isValidRequest($request);
        
        $id = (int) $request->get('user_following_id');
        $user_id = (int) $this->getUser()['user_id'];

        if ($is_valid_request && $this->user_validator->validate($params)) {// valid data
            $uniqueObj = $this->obj_item->uniqueObj($id);
            $item = $this->obj_item->findIDUser($id);
            // update existing item
            if (!empty($item)){
                // var_dump($item->follow_flag);die();
                if ($item->follow_flag!=NULL) {
                    $params['id'] = $id;
                    $item = $this->obj_item->unFollow($params);

                    // message
                    return Redirect::route($this->root_router.'.list', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_admin.'.actions.unfollow-ok'));
                } else {
                    $params['id'] = $id;
                    $item = $this->obj_item->reFollow($params);
                    // message
                    return Redirect::route($this->root_router.'.list')
                                    ->withMessage(trans($this->plang_admin.'.actions.follow-ok'));
                }

            // add new item
            } else {
            if ($id != $user_id && empty($uniqueObj)) {
                // message
                $params['id'] = $id;
                $params['follow_flag'] = 1;
                $item = $this->obj_item->insertItem($params);
                return Redirect::route($this->root_router.'.list', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_admin.'.actions.follow-ok'));
            } else {
                return Redirect::route($this->root_router.'.list')
                                    ->withMessage(trans($this->plang_admin.'.actions.follow-error'));
            }
        } 
    } else { // invalid data
            $errors = $this->obj_validator->getErrors();

            // passing the id incase fails editing an already existing item
            return Redirect::route($this->root_router.'.add', $id ? ["id" => $id]: [])
                    ->withInput()->withErrors($errors);
        }
    }



}
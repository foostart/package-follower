<?php namespace Foostart\Follower\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Foostart\Category\Library\Controllers\FooController;
use URL,
    Route,
    Redirect;
use Foostart\Follower\Models\Follower;
use Illuminate\Support\Facades\App;
use Foostart\Follower\Repository\FollowRepository;
use Foostart\Follower\Validators\FollowerValidator;
use LaravelAcl\Authentication\Validators\UserValidator;
class FollowerUserController extends FooController
{
    public $obj_item = NULL;
    protected $user_repository;
    protected $follow_repository;
    protected $user_validator;
    public function __construct() {

        parent::__construct();
        // models
        $this->obj_item = new Follower(array('perPage' => 10));
        $this->user_repository = App::make('user_repository');
        $this->follow_repository = new FollowRepository();
        // validators
        $this->obj_validator = new FollowerValidator();
        $this->user_validator = new UserValidator();
        // set language files
        $this->plang_user = 'follower-user';
        $this->plang_front = 'follower-front';

        // package name
        $this->package_name = 'package-follower';
        $this->package_base_name = 'follower';

        // root routers
        $this->root_router = 'followers';

        // page views
        $this->page_views = [
            'user' => [
                'items' => $this->package_name.'::user.'.$this->package_base_name.'-items',
                'edit'  => $this->package_name.'::user.'.$this->package_base_name.'-edit',
            ]
        ];

        $this->data_view['status'] = $this->obj_item->getPluckStatus();

        // //set category
        $this->category_ref_name = 'user/followers';
        $this->statuses = config('package-follower.status.list');
    }

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

        return view($this->page_views['user']['items'], $this->data_view);
    }
    
    public function userAdd(Request $request) {
        $user_leader = $this->user_repository->isLeader();
        $users = $this->follow_repository->getlistUser($request);
        
        $this->data_view = array_merge($this->data_view, array(
            "users" => $users, 
            "request" => $request, 
            'statuses' => $this->statuses,
        ));
        return view($this->page_views['user']['edit'], $this->data_view);
    }

    /**
     * Processing data from POST method: add new item, edit existing item
     * @return view edit page
     * @date 27/12/2017
     */
    public function userPostAdd(Request $request) {
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
                    return Redirect::route($this->root_router.'.sample', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_user.'.actions.unfollow-ok'));
                } else {
                    $params['id'] = $id;
                    $item = $this->obj_item->reFollow($params);
                    // message
                    return Redirect::route($this->root_router.'.sample')
                                    ->withMessage(trans($this->plang_user.'.actions.follow-ok'));
                }

            // add new item
            } else {
                if ($id != $user_id && empty($uniqueObj)) {
                // message
                $params['id'] = $id;
                $params['follow_flag'] = 1;
                $item = $this->obj_item->insertItem($params);
                return Redirect::route($this->root_router.'.sample', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_user.'.actions.follow-ok'));
                }else {
                    return Redirect::route($this->root_router.'.sample')
                                    ->withMessage(trans($this->plang_user.'.actions.follow-error'));
                }
            } 
        }   else { // invalid data
            $errors = $this->obj_validator->getErrors();

            // passing the id incase fails editing an already existing item
            return Redirect::route($this->root_router.'.userAdd', $id ? ["id" => $id]: [])
                    ->withInput()->withErrors($errors);
        }
    }

}
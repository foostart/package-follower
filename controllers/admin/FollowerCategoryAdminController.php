<?php namespace Foostart\Follower\Controllers\Admin;

use Illuminate\Http\Request;
use Foostart\Follower\Controllers\Admin\Controller;
use URL;
use Route,
    Redirect;
use Foostart\Follower\Models\FollowersCategories;
/**
 * Validators
 */
use Foostart\Follower\Validators\FollowerCategoryAdminValidator;

class FollowerCategoryAdminController extends Controller {

    public $data_view = array();
    
    private $obj_follower_category = NULL;
    private $obj_validator = NULL;

    public function __construct() {
        $this->obj_follower_category = new FollowersCategories();
    }

    /**
     *
     * @return type
     */
    public function index(Request $request) {

         $params =  $request->all();

        $list_follower_category = $this->obj_follower_category->get_followers_categories($params);

        $this->data_view = array_merge($this->data_view, array(
            'followers_categories' => $list_follower_category,
            'request' => $request,
            'params' => $params
        ));
        return view('follower::follower_category.admin.follower_category_list', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function edit(Request $request) {

        $follower = NULL;
        $follower_id = (int) $request->get('id');


        if (!empty($follower_id) && (is_int($follower_id))) {
            $follower = $this->obj_follower_category->find($follower_id);

        }

        $this->data_view = array_merge($this->data_view, array(
            'follower' => $follower,
            'request' => $request
        ));
        return view('follower::follower_category.admin.follower_category_edit', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function post(Request $request) {

        $this->obj_validator = new FollowerCategoryAdminValidator();

        $input = $request->all();

        $follower_id = (int) $request->get('id');

        $follower = NULL;

        $data = array();

        if (!$this->obj_validator->validate($input)) {

            $data['errors'] = $this->obj_validator->getErrors();

            if (!empty($follower_id) && is_int($follower_id)) {

                $follower = $this->obj_follower_category->find($follower_id);
            }

        } else {
            if (!empty($follower_id) && is_int($follower_id)) {

                $follower = $this->obj_follower_category->find($follower_id);

                if (!empty($follower)) {

                    $input['follower_category_id'] = $follower_id;
                    $follower = $this->obj_follower_category->update_follower_category($input);

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_update_successfully'));
                    return Redirect::route("admin_follower_category.edit", ["id" => $follower->follower_id]);
                } else {

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_update_unsuccessfully'));
                }
            } else {

                $follower = $this->obj_follower_category->add_follower_category($input);

                if (!empty($follower)) {

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_add_successfully'));
                    return Redirect::route("admin_follower_category.edit", ["id" => $follower->follower_id]);
                } else {

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_add_unsuccessfully'));
                }
            }
        }

        $this->data_view = array_merge($this->data_view, array(
            'follower' => $follower,
            'request' => $request,
        ), $data);

        return view('follower::follower_category.admin.follower_category_edit', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function delete(Request $request) {

        $follower = NULL;
        $follower_id = $request->get('id');

        if (!empty($follower_id)) {
            $follower = $this->obj_follower_category->find($follower_id);

            if (!empty($follower)) {
                  //Message
                $this->addFlashMessage('message', trans('follower::follower_admin.message_delete_successfully'));

                $follower->delete();
            }
        } else {

        }

        $this->data_view = array_merge($this->data_view, array(
            'follower' => $follower,
        ));

        return Redirect::route("admin_follower_category");
    }

}
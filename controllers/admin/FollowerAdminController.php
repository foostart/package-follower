<?php

namespace Foostart\Follower\Controllers\Admin;

use Illuminate\Http\Request;
use Foostart\Follower\Controllers\Admin\Controller;
use URL;
use Route,
    Redirect;
use Foostart\Follower\Models\Followers;
use Foostart\Follower\Models\FollowersCategories;
/**
 * Validators
 */
use Foostart\Follower\Validators\FollowerAdminValidator;

class FollowerAdminController extends Controller {

    public $data_view = array();
    private $obj_follower = NULL;
    private $obj_follower_categories = NULL;
    private $obj_validator = NULL;

    public function __construct() {
        $this->obj_follower = new Followers();
    }

    /**
     *
     * @return type
     */
    public function index(Request $request) {

        $params = $request->all();

        $list_follower = $this->obj_follower->get_followers($params);

        $this->data_view = array_merge($this->data_view, array(
            'followers' => $list_follower,
            'request' => $request,
            'params' => $params
        ));
        return view('follower::follower.admin.follower_list', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function edit(Request $request) {

        $follower = NULL;
        $follower_id = (int) $request->get('id');


        if (!empty($follower_id) && (is_int($follower_id))) {
            $follower = $this->obj_follower->find($follower_id);
        }

        $this->obj_follower_categories = new followersCategories();

        $this->data_view = array_merge($this->data_view, array(
            'follower' => $follower,
            'request' => $request,
            'categories' => $this->obj_follower_categories->pluckSelect()
        ));
        return view('follower::follower.admin.follower_edit', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function post(Request $request) {

        $this->obj_validator = new followerAdminValidator();

        $input = $request->all();

        $follower_id = (int) $request->get('id');
        $follower = NULL;

        $data = array();

        if (!$this->obj_validator->validate($input)) {

            $data['errors'] = $this->obj_validator->getErrors();

            if (!empty($follower_id) && is_int($follower_id)) {

                $follower = $this->obj_follower->find($follower_id);
            }
        } else {
            if (!empty($follower_id) && is_int($follower_id)) {

                $follower = $this->obj_follower->find($follower_id);

                if (!empty($follower)) {

                    $input['follower_id'] = $follower_id;
                    $follower = $this->obj_follower->update_follower($input);

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_update_successfully'));
                    return Redirect::route("admin_follower.edit", ["id" => $follower->follower_id]);
                } else {

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_update_unsuccessfully'));
                }
            } else {

                $follower = $this->obj_follower->add_follower($input);

                if (!empty($follower)) {

                    //Message
                    $this->addFlashMessage('message', trans('follower::follower_admin.message_add_successfully'));
                    return Redirect::route("admin_follower.edit", ["id" => $follower->follower_id]);
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

        return view('follower::follower.admin.follower_edit', $this->data_view);
    }

    /**
     *
     * @return type
     */
    public function delete(Request $request) {

        $follower = NULL;
        $follower_id = $request->get('id');

        if (!empty($follower_id)) {
            $follower = $this->obj_follower->find($follower_id);

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

        return Redirect::route("admin_follower");
    }

}

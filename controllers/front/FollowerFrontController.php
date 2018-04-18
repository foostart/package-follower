<?php

namespace Foostart\Follower\Controlers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use URL,
    Route,
    Redirect;
use Foostart\Follower\Models\Followers;

class FollowerFrontController extends Controller
{
    public $data = array();
    public function __construct() {

    }

    public function index(Request $request)
    {

        $obj_follower = new Followers();
        $followers = $obj_follower->get_followers();
        $this->data = array(
            'request' => $request,
            'followers' => $followers
        );
        return view('follower::follower.index', $this->data);
    }

}
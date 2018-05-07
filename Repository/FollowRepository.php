<?php namespace Foostart\Follower\Repository;

/*
|-----------------------------------------------------------------------
| FollowerRepository
|-----------------------------------------------------------------------
| @author: Kaffeines
| @website: http://foostart.com
| @date: 03/05/2018
|
*/
use LaravelAcl\Authentication\Repository\UserRepositorySearchFilter;
use Foostart\Follower\Models\Follower;
use DB;

class FollowRepository extends UserRepositorySearchFilter {
    private $follow_table_name = "followers";


    public function getlistUser($request)
    {
        $list = $this->all($request->except(['page']));
        foreach ($list as $listKey) {
            $flag = $this->getFollowFlag($listKey->id);
            foreach ($flag as $key => $value) {
               $listKey->follow_flag = $value->follow_flag;  
           }
            
        }
        return $list;
    }

    public function getFollowFlag($id)
    {
        return  DB::table('followers')->where('user_following_id',$id)->get(['follow_flag']);
      
    }
}

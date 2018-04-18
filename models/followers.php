<?php

namespace Foostart\Follower\Models;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model {

    protected $table = 'followers';
    public $timestamps = false;
    protected $fillable = [
        'follower_name',
        'category_id'
    ];
    protected $primaryKey = 'follower_id';
    /**
     *
     * @param type $params
     * @return type
     */
    public function get_followers($params = array()) {
        $eloquent = self::orderBy('follower_id');

        //follower_name
        if (!empty($params['follower_name'])) {
            $eloquent->where('follower_name', 'like', '%'. $params['follower_name'].'%');
        }

        $followers = $eloquent->paginate(10);//TODO: change number of item per page to configs

        return $followers;
    }



    /**
     *
     * @param type $input
     * @param type $follower_id
     * @return type
     */
    public function update_follower($input, $follower_id = NULL) {

        if (empty($follower_id)) {
            $follower_id = $input['follower_id'];
        }

        $follower = self::find($follower_id);

        if (!empty($follower)) {

            $follower->follower_name = $input['follower_name'];
            $follower->category_id = $input['category_id'];

            $follower->save();

            return $follower;
        } else {
            return NULL;
        }
    }

    /**
     *
     * @param type $input
     * @return type
     */
    public function add_follower($input) {
        $follower = self::create([
                    'follower_name' => $input['follower_name'],
                    'category_id' => $input['category_id'],
        ]);
        return $follower;
    }
}

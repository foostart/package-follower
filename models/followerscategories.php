<?php

namespace Foostart\Follower\Models;

use Illuminate\Database\Eloquent\Model;

class FollowersCategories extends Model {

    protected $table = 'followers_categories';
    public $timestamps = false;
    protected $fillable = [
        'follower_category_name'
    ];
    protected $primaryKey = 'follower_category_id';

    public function get_followers_categories($params = array()) {
        $eloquent = self::orderBy('follower_category_id');

        if (!empty($params['follower_category_name'])) {
            $eloquent->where('follower_category_name', 'like', '%'. $params['follower_category_name'].'%');
        }
        $followers_category = $eloquent->paginate(10);
        return $followers_category;
    }

    /**
     *
     * @param type $input
     * @param type $follower_id
     * @return type
     */
    public function update_follower_category($input, $follower_id = NULL) {

        if (empty($follower_id)) {
            $follower_id = $input['follower_category_id'];
        }

        $follower = self::find($follower_id);

        if (!empty($follower)) {

            $follower->follower_category_name = $input['follower_category_name'];

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
    public function add_follower_category($input) {

        $follower = self::create([
                    'follower_category_name' => $input['follower_category_name'],
        ]);
        return $follower;
    }

    /**
     * Get list of followers categories
     * @param type $category_id
     * @return type
     */
     public function pluckSelect($category_id = NULL) {
        if ($category_id) {
            $categories = self::where('follower_category_id', '!=', $category_id)
                    ->orderBy('follower_category_name', 'ASC')
                ->pluck('follower_category_name', 'follower_category_id');
        } else {
            $categories = self::orderBy('follower_category_name', 'ASC')
                ->pluck('follower_category_name', 'follower_category_id');
        }
        return $categories;
    }

}

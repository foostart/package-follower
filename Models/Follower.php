<?php namespace Foostart\Follower\Models;

use Foostart\Category\Library\Models\FooModel;
use Illuminate\Database\Eloquent\Model;
use DB;


class Follower extends FooModel {

    /**
     * @table categories
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        //set configurations
        $this->setConfigs();

        parent::__construct($attributes);

    }

    public function setConfigs() {

        //table name
        $this->table = 'followers';

        //list of field in table
        $this->fillable = [
            'user_following_id',
            'follower_status',
            'follow_flag',
            'created_at',
            'updated_at',
        ];

        //list of fields for inserting
        $this->fields = [
            'user_following_id' => [
                'name' => 'user_following_id',
                'type' => 'Int',
            ],
            'follower_status' => [
                'name' => 'follower_status',
                'type' => 'Int',
            ],
            'follow_flag' => [
                'name' => 'follow_flag',
                'type' => 'Int',
            ],
        ];

        //check valid fields for inserting
        $this->valid_insert_fields = [
            'user_following_id',
            'follower_status',
            'created_at',
            'updated_at',
        ];

        //check valid fields for ordering
        $this->valid_ordering_fields = [
            'user_following_id',
            'follower_status',
            'created_at',
            'updated_at',
            $this->field_status,
        ];
        //check valid fields for filter
        $this->valid_filter_fields = [
            'keyword',
            'status',
        ];

        //primary key
        $this->primaryKey = 'follower_id';

        //the number of items on page
        $this->perPage = 10;

        //item status
        $this->field_status = 'follower_status';

    }

    /**
     * Gest list of items
     * @param type $params
     * @return object list of categories
     */
    public function selectItems($params = array()) {

        //join to another tables
        $elo = $this->joinTable();
        //search filters

        $elo = $this->searchFilters($params, $elo);

        //select fields
        $elo = $this->createSelect($elo);

        //order filters
        $elo = $this->orderingFilters($params, $elo);

        //paginate items
        $items = $this->paginateItems($params, $elo);

        return $items;
    }

    /**
     * Get a follower by {id}
     * @param ARRAY $params list of parameters
     * @return OBJECT follower
     */
    public function selectItem($params = array(), $key = NULL) {


        if (empty($key)) {
            $key = $this->primaryKey;
        }
       //join to another tables
        $elo = $this->joinTable();
        
        //search filters
        $elo = $this->searchFilters($params, $elo, FALSE);

        //select fields
        $elo = $this->createSelect($elo);

        //id
        $elo = $elo->where($this->primaryKey, $params['id']);

        //first item
        $item = $elo->first();

        return $item;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function joinTable(array $params = []){
        return $this;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function searchFilters(array $params = [], $elo, $by_status = TRUE){

        //filter
        if ($this->isValidFilters($params) && (!empty($params)))
        {
            foreach($params as $column => $value)
            {
                if($this->isValidValue($value))
                {
                    switch($column)
                    {
                        case 'follower_name':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.follower_name', '=', $value);
                            }
                            break;
                        case 'status':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.'.$this->field_status, '=', $value);
                            }
                            break;
                        case 'keyword':
                            if (!empty($value)) {
                                $elo = $elo->where(function($elo) use ($value) {
                                    $elo->where($this->table . '.follower_name', 'LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.follower_description','LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.follower_overview','LIKE', "%{$value}%");
                                });
                            }
                            break;
                        default:
                            break;
                    }
                }
            }

        }
            /*
            *
            *Get by status
            *

            elseif ($by_status) {

            $elo = $elo->where($this->table . '.'.$this->field_status, '=', $this->status['publish']);

        }
            */
     

        return $elo;
    }

    /**
     * Select list of columns in table
     * @param ELOQUENT OBJECT
     * @return ELOQUENT OBJECT
     */
    public function createSelect($elo) {

        $elo = $elo
        ->where('followers.follow_flag',1)
        ->join('users','followers.user_following_id', '=', 'users.id')
        ->join('user_profile','followers.user_following_id', '=', 'user_profile.user_id')
        ->select('users.email','users.activated','users.id','user_profile.first_name','user_profile.last_name','followers.*');
        return $elo;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    public function paginateItems(array $params = [], $elo) {
        $items = $elo->paginate($this->perPage);

        return $items;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @param INT $id is primary key
     * @return type
     */
    public function updateItem($params = [], $id = NULL) {

        if (empty($id)) {
            $id = $params['id'];
        }
        $field_status = $this->field_status;

        $follower = $this->selectItem($params);

        if (!empty($follower)) {
            $dataFields = $this->getDataFields($params, $this->fields);

            foreach ($dataFields as $key => $value) {
                $follower->$key = $value;
            }



            $follower->save();

            return $follower;
        } else {
            return NULL;
        }
    }


    /**
     *
     * @param ARRAY $params list of parameters
     * @return OBJECT follower
     */
    public function insertItem($params = []) {

        $dataFields = $this->getDataFields($params, $this->fields);

        //$dataFields[$this->field_status] = $this->status['publish'];
            try {
                
                 $item = self::create($dataFields);

            } catch (Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];

            }


        $key = $this->primaryKey;
        $item->id = $item->$key;

        return $item;
    }


    /**
     *
     * @param ARRAY $input list of parameters
     * @return boolean TRUE incase delete successfully otherwise return FALSE
     */
    public function deleteItem($input = [], $delete_type) {

        $item = $this->find($input['id']);

        if ($item) {
            switch ($delete_type) {
                case 'delete-trash':
                    return $item->fdelete($item);
                    break;
                case 'delete-forever':
                    return $item->delete();
                    break;
            }

        }

        return FALSE;
    }
    public function uniqueObj($user_following_id) {
            if (!empty($this->where('user_following_id',$user_following_id)->first())) {
                return TRUE;
            } else{
                return FALSE;
            }
    }
    public function unFollow($input = []) {

        $this->where('user_following_id',$input['id'])->update(['follow_flag' => NULL]); 
        return $this->where('user_following_id',$input['id'])->first();
    }
    public function reFollow($input = []) {

        $this->where('user_following_id',$input['id'])->update(['follow_flag' => 1]); 
        return $this->where('user_following_id',$input['id'])->first();
    }
    public function findIDUser($id) {
        return $this->where('user_following_id',$id)->first();
    }
}
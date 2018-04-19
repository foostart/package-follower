<?php namespace Foostart\Follower\Models;

use Foostart\Category\Library\Models\FooModel;
use Illuminate\Database\Eloquent\Model;

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
            'follower_name',
            'category_id',
            'user_id',
            'user_full_name',
            'user_email',
            'follower_overview',
            'follower_description',
            'follower_image',
            'follower_files',
            'follower_status',
        ];

        //list of fields for inserting
        $this->fields = [
            'follower_name' => [
                'name' => 'follower_name',
                'type' => 'Text',
            ],
            'category_id' => [
                'name' => 'category_id',
                'type' => 'Int',
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => 'Int',
            ],
            'user_full_name' => [
                'name' => 'user_full_name',
                'type' => 'Text',
            ],
            'user_email' => [
                'name' => 'email',
                'type' => 'Text',
            ],
            'follower_overview' => [
                'name' => 'follower_overview',
                'type' => 'Text',
            ],
            'follower_description' => [
                'name' => 'follower_description',
                'type' => 'Text',
            ],
            'follower_image' => [
                'name' => 'follower_image',
                'type' => 'Text',
            ],
            'follower_files' => [
                'name' => 'files',
                'type' => 'Json',
            ],
        ];

        //check valid fields for inserting
        $this->valid_insert_fields = [
            'follower_name',
            'user_id',
            'category_id',
            'user_full_name',
            'updated_at',
            'follower_overview',
            'follower_description',
            'follower_image',
            'follower_files',
            'follower_status',
        ];

        //check valid fields for ordering
        $this->valid_ordering_fields = [
            'follower_name',
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
        } elseif ($by_status) {

            $elo = $elo->where($this->table . '.'.$this->field_status, '=', $this->status['publish']);

        }

        return $elo;
    }

    /**
     * Select list of columns in table
     * @param ELOQUENT OBJECT
     * @return ELOQUENT OBJECT
     */
    public function createSelect($elo) {

        $elo = $elo->select($this->table . '.*',
                            $this->table . '.follower_id as id'
                );

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

            $follower->$field_status = $this->status['publish'];

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

        $dataFields[$this->field_status] = $this->status['publish'];


        $item = self::create($dataFields);

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

}
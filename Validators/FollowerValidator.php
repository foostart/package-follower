<?php namespace Foostart\Follower\Validators;

use Foostart\Category\Library\Validators\FooValidator;
use Event;
use \LaravelAcl\Library\Validators\AbstractValidator;
use Foostart\Follower\Models\Follower;

use Illuminate\Support\MessageBag as MessageBag;

class FollowerValidator extends FooValidator
{

    protected $obj_follower;

    public function __construct()
    {
        // add rules
        self::$rules = [
            'follower_name' => ["required"],
            'follower_overview' => ["required"],
            'follower_description' => ["required"],
        ];

        // set configs
        self::$configs = $this->loadConfigs();

        // model
        $this->obj_follower = new Follower();

        // language
        $this->lang_front = 'follower-front';
        $this->lang_admin = 'follower-admin';

        // event listening
        Event::listen('validating', function($input)
        {
            self::$messages = [
                'follower_name.required'          => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.name')]),
                'follower_overview.required'      => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.overview')]),
                'follower_description.required'   => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.description')]),
            ];
        });


    }

    /**
     *
     * @param ARRAY $input is form data
     * @return type
     */
    public function validate($input) {

        $flag = parent::validate($input);
        $this->errors = $this->errors ? $this->errors : new MessageBag();

        //Check length
        $_ln = self::$configs['length'];

        $params = [
            'name' => [
                'key' => 'follower_name',
                'label' => trans($this->lang_admin.'.fields.name'),
                'min' => $_ln['follower_name']['min'],
                'max' => $_ln['follower_name']['max'],
            ],
            'overview' => [
                'key' => 'follower_overview',
                'label' => trans($this->lang_admin.'.fields.overview'),
                'min' => $_ln['follower_overview']['min'],
                'max' => $_ln['follower_overview']['max'],
            ],
            'description' => [
                'key' => 'follower_description',
                'label' => trans($this->lang_admin.'.fields.description'),
                'min' => $_ln['follower_description']['min'],
                'max' => $_ln['follower_description']['max'],
            ],
        ];

        $flag = $this->isValidLength($input['follower_name'], $params['name']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['follower_overview'], $params['overview']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['follower_description'], $params['description']) ? $flag : FALSE;

        return $flag;
    }


    /**
     * Load configuration
     * @return ARRAY $configs list of configurations
     */
    public function loadConfigs(){

        $configs = config('package-follower');
        return $configs;
    }

}
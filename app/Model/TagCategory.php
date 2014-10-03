<?php

App::uses('AppModel', 'Model');

/**
 * TagCategory Model
 *
 */
class TagCategory extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'delete_flag' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                ),
            ),
        );
    }

    public function createValidate() {
        //return true;
        $validate1 = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("name not null ")
                ),
            ),
        );
        $this->validate = $validate1;
        return $this->validates($this->data);
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Tag' => array(
            'className' => 'Tag',
            'foreignKey' => 'category_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );

}

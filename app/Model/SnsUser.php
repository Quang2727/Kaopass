<?php
App::uses('AppModel', 'Model');
/**
 * SnsUser Model
 *
 * @property User $User
 */
class SnsUser extends AppModel {

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
}

<?php
App::uses('AppModel', 'Model');
/**
 * SnsFriendUser Model
 *
 * @property User $User
 */
class SnsFriendUser extends AppModel {

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

    public function deleteByUserId($params) {
        $this->deleteAll(array(
            "user_id" => $params["user_id"],
            "sns_type" => $params["sns_type"]
        ),false);
    }
}

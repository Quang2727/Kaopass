<?php
App::uses('AppModel', 'Model');
/**
 * UserInfo Model
 *
 * @property User $User
 */
class UserInfo extends AppModel {

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

        );
    }
}

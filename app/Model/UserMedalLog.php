<?php

App::uses('AppModel', 'Model');

/**
 * UserMedalLog Model
 *
 */
class UserMedalLog extends AppModel {

    public function createValidate() {

    }

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array();
    }
}

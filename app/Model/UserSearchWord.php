<?php

App::uses('AppModel', 'Model');

/**
 * SearchQuestion Model
 *
 */
class UserSearchWord extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }
}

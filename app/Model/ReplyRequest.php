<?php
App::uses('AppModel', 'Model');
/**
 * ReplyRequest Model
 *
 * @property User $User
 * @property TargetUser $TargetUser
 * @property Question $Question
 */
class ReplyRequest extends AppModel {

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

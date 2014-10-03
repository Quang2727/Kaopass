<?php

App::uses('AppModel', 'Model');

/**
 * LoginHistory Model
 *
 */
class LoginHistory extends AppModel {
    
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
            'user_id' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG002')
                ),
                'numeric' => array(
                    'rule' => 'numeric'
                )
            ),
            'login_type' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG003')
                ),
                'numeric' => array(
                    'rule' => 'numeric'
                )                
            ),
            'sns_name' => array(
                'length' => array(
                    'rule' => array('maxLength', 20),
                    'message' => __('A03000_ERR_MSG004', 20)
                ),
            )            
        );
    }
}

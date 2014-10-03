<?php

App::uses('AppModel', 'Model');

/**
 * UserMedal Model
 *
 */
class UserMedal extends AppModel {

    public function createValidate() {
        $validate = array(
//            'body' => array(
//                'rule1' => array(
//                    'rule' => 'notEmpty',
//                    'message' => __("A01110_ERR_MSG003")
//                ),
//                'rule2' => array(
//                        'rule' => array('maxLength', 1000),
//                    'message' => __("A01110_ERR_MSG004", 1000)
//                ),
//            ),
        );
        $this->validate = $validate;
        return $this->validates($this->data);
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

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    /**
     * hasMany associations
     *
     * @var array
     */
/*
    public $hasMany = array(
        'UserMedalLog' => array(
            'className' => 'UserMedalLog',
            'foreignKey' => 'user_medal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
*/
    public function getRelease($params) {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'UserMedal.medal_id in' => $params['media_ids'],
                    'UserMedal.user_id' => $params['user_id'],
                    'UserMedal.delete_flag' => FLAG_NOT_DELETED
                ),
            )
        );                
    }
    
    public function getReleaseForFirst($params) {
        return $this->find(
            'first',
            array(
                "conditions" => array(
                    "UserMedal.medal_id" => $params['medal_id'],
                    "UserMedal.user_id" => $params['user_id'],
                    "UserMedal.delete_flag" => FLAG_NOT_DELETED
                )
            )
        );        
    }

    /**
     * バッジ取得情報を確認し、挿入or更新を行う。
     */
    public function increaseCounter($params) {
        $user_data = $this->getReleaseForFirst($params);
        if(!$user_data) { //バッチ取得情報を挿入する
            $count = 1;
            if (isset($params['counter']) === false || (int)$params['counter'] === 0) {
                $params['counter'] = 1;
            }
            $user_medal_id = $this->_insert($params);
        } else { //バッチ取得数を更新する
            $user_medal_id = $this->_updateCounter($user_data['UserMedal']);
            $count = $user_data['UserMedal']['counter'] + 1;
        }

	return array($user_medal_id, $count);
    }

    protected function _insert($params) {
	$this->create();
        $this->save($params); 
        return $this->getLastInsertID();
    }

    protected function _updateCounter($params) {
	$counter = $params["counter"] + 1;
	$this->id = $params["id"];
	$this->save(array(
	    "counter" => $counter
	));
	return $params["id"];
    }
}

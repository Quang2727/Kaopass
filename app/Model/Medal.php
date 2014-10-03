<?php

App::uses('AppModel', 'Model');

/**
 * Medal Model
 *
 */
class Medal extends AppModel {

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

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'UserMedal' => array(
            'className' => 'UserMedal',
            'foreignKey' => 'medal_id',
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

    /**
     * 公開中のすべてのバッチから情報一覧を取得する。
     * 
     * 
     * @param type $params
     * @return type
     */
    public function getRelease($params) {
        return $this->find('all',$this->_getReleaseData($params));
    }

    /**
     * 公開中のすべてのバッチから情報一覧を取得する。
     * 
     * 
     * @param type $params
     * @return type
     */
    public function getReleaseForList($params) {
        return $this->find('list',$this->_getReleaseData($params));
    }
    
    /**
     * 公開中のすべてのバッチから情報一覧を取得する条件を取得する。
     * 
     * @param type $params
     * @return type
     */
    protected function _getReleaseData($params) {
        return array(
            'conditions' => array(
                'Medal.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => 'Medal.sort asc, Medal.created desc',
//            'limit' => BADGE_LIMIT
        );        
    }

    /**
     * 公開中のタイプ別のバッチから情報一覧を取得する。
     * 
     * 
     * @param type $params
     * @return type
     */
    public function getReleaseByType($params) {
        return $this->find('all',$this->_getReleaseDataByType($params));
    }

    /**
     * 公開中のタイプ別のバッチから情報一覧を取得する。
     * 
     * 
     * @param type $params
     * @return type
     */
    public function getReleaseForListByType($params) {
        return $this->find('list',$this->_getReleaseDataByType($params));
    }

    /**
     * 公開中のタイプ別のバッチから情報一覧を取得する条件を取得する。
     * 
     * @param type $params
     * @return type
     */
    public function _getReleaseDataByType($params) {
        $data = array(
            'conditions' => array(
                'Medal.type' => $params['type'],
                'Medal.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => 'Medal.sort asc, Medal.created desc',
            'limit' => BADGE_LIMIT            
        );
        
        return $this->find('all',$data);        
    }

    /**
     * ルール名を条件に情報を取得する。
     * 
     * @param type $params
     * @return type
     */
    public function getReleaseByRuleName($params) {
            return $this->find(
                'first',
                array(
                    "conditions" => array(
                        "Medal.rule_name" => $params['rule_name'],
                        "Medal.delete_flag" => FLAG_NOT_DELETED
                    )
                )
            );        
    }

    /**
     * ユーザが対象のメダルを取得しているか取得。
     * 
     * @param type $params
     * @return type
     */
    public function getInfoByUserIdAndRuleName($params) {
            return $this->find(
                'all',
                array(
                    "conditions" => array(
                        "Medal.rule_name" => $params['rule_name'],
                        "Medal.delete_flag" => FLAG_NOT_DELETED
                    ),
                    "joins" => array(
                        array(
                            "type" => "INNER",
                            "table" => "user_medals",
                            "alias" => "UserMedal",
                            "conditions" => array(
                                                "UserMedal.medal_id = Medal.id",
                                                "UserMedal.user_id" => $params['user_id']
                                            )
                        ),
                    ),
                )
            );        
    }
}

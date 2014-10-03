<?php

App::uses('AppModel', 'Model');

/**
 * UserTag Model
 *
 * @property User $User
 * @property Tag $Tag
 */
class UserTag extends AppModel {

    public $displayField = 'tag_id';

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


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Tag' => array(
            'className' => 'Tag',
            'foreignKey' => 'tag_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'type' => 'LEFT'
        )
    );
    
    /**
     * サービス上で使用可能なユーザにひもづくタグ情報を取得する。
     * 
     * @param type $params
     * @return type
     */
    public function getReleaseByUserId($params) {
        if (!isset($params["user_id"])) {
            return array();
        }
        $this->displayField = 'tag_id';
        return $this->find('list', array(
            'conditions' => array(
                'UserTag.user_id' => $params["user_id"],
                'UserTag.delete_flag' => FLAG_NOT_DELETED
            ),
            'cache' => 'default'
        ));        
    }
    
    /**
     * サービス上で使用可能なユーザにひもづくタグ情報をランダムで取得する。
     * 
     * @param type $params
     * @return type
     */
    public function getRandomByUserId($params) {
        if (!isset($params["user_id"])) {
            return array();
        }
        return $this->find('list', array(
            'conditions' => array(
                'UserTag.user_id' => $params["user_id"],
                'UserTag.delete_flag' => FLAG_NOT_DELETED
            ),
            'fields' => array(
                'UserTag.tag_id',
                'UserTag.tag_id'
            ),
            'order' => 'random()'
        ));        
    }

    /**
     * 次の形式でuser_tagsテーブルより情報抽出。
     * array(
     *     [user_id] => array(
     *         [tag_id],
     *     ),
     * )
     * 
     * @param array $userIdList users.idの配列
     * @param int $length user_id毎に抽出する最大タグ数
     * @return array 条件に該当するデータ
     */
    public function getListByUserIdList($userIdList, $length = 3) {
        if (!$userIdList) {
            return array();
        }

        $dbo = $this->getDataSource();
        $subQuery = $dbo->buildStatement(
            array(
                'fields' => array('"UserTag2"."id"'),
                'table' => $dbo->fullTableName($this),
                'alias' => 'UserTag2',
                'limit' => $length,
                'conditions' => array(
                    'UserTag2.user_id = UserTag.user_id',
                    'UserTag2.delete_flag' => FLAG_NOT_DELETED
                ),
                'order' => array('UserTag2.tag_id ASC'),
            ),
            $this
        );
        $subQuery = ' "UserTag"."id" IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);

        $conditions[] = $subQueryExpression;
        $conditions = compact('conditions');

        $result = $this->find('list', array(
            'fields' => array('UserTag.id', 'UserTag.tag_id', 'UserTag.user_id'),
            'conditions' => array_merge(array(
                'UserTag.user_id' => $userIdList,
            ), $conditions['conditions']),
            'order' => array('UserTag.tag_id ASC'),
        ));

        $data = array();
        foreach ($result as $user_id => $array) {
            $data[$user_id] = array_values($array);
        }
        return $data;
    }

    /**
     * タグIDを一覧として取得する。
     */
    public function getMyTagList($userId) {
        $this->displayField = 'tag_id';
        return $this->find('list', array(
            'conditions' => array('user_id' => $userId)
        ));
    }
}

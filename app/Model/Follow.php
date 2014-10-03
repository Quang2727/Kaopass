<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 * @property User $User
 * @property Target $Target
 */
class Follow extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Follower' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => '',
            'order' => ''
        ),
        'Following' => array(
            'className' => 'User',
            'foreignKey' => 'follow_user_id',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * アソシエーション全解除
     */
    public function unbindAllAsociation() {
        $this->unBindModel(array(
            'belongsTo' => array(
                "Follower",
                "Following"
            )
        ));
    }

    /**
     * フォローしているユーザ一覧を取得する。
     */
    public function getFollowUser($params) {
        return $this->find("list", array(
            'conditions' => array(
                'Follow.user_id' => $params['user_id']
            ),
            'fields' => array(
                "Follow.id",
                "Follow.follow_user_id"
            )
        ));        
    }

    /**
     * フォローしているユーザ一覧をユーザIDで取得する。
     * 
     * @param type $params
     * @return type
     */
    public function getFollowUserIdList($params) {
        $this->displayField = 'follow_user_id';
        return $this->find("list", array(
            'conditions' => array(
                'Follow.user_id' => $params['user_id']
            )
        ));        
    }

    public function countAllGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(follow_user_id)');
        return $this->find('list', array(
            "fields" => array("follow_user_id", "count"),
            "conditions" => array(
                "created <" => date("Y-m-d", strtotime("now")),
            ),
            "group" => "follow_user_id",
            "order" => array("follow_user_id asc")
        ));
    }

    public function countTermGroupUserId($days) {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(follow_user_id)');
        return $this->find('list', array(
            "fields" => array("follow_user_id", "count"),
            "conditions" => array(
                "created <" => date("Y-m-d", strtotime("now")),
                "created >=" => date("Y-m-d", strtotime("-$days days")),
            ),
            "group" => "follow_user_id",
            "order" => array("follow_user_id asc")
        ));        
    }

    /**
     * フォロワー情報を取得する。
     */
    public function getFollower($user_id,$order=array('created' => 'desc'),$limit=4) {
        $this->recursive = 0;
        $conditions = array(
            'follow_user_id' => $user_id
        );
        return array(
                   $this->_getList($conditions,$order,$limit),
                   $this->_getCount($conditions)
               );
    }

    /**
     * フォロー情報を取得する。
     */
    public function getFollowing($user_id,$order=array('created' => 'desc'),$limit=4) {
        $this->recursive = 0;
        $conditions = array(
            'user_id' => $user_id
        );
        return array(
                   $this->_getList($conditions,$order,$limit),
                   $this->_getCount($conditions)
               );
    }

    protected function _getList($conditions,$order,$limit=10) {
        return $this->find('all',array(
            'conditions' => $conditions,
            'order' => $order,
            'limit' => $limit,
        ));
    }

    protected function _getCount($conditions) {
        return $this->find('count',array(
            'conditions' => $conditions,
        ));
    }

    /**
     * アクセス者がフォローしている場合に情報を取得する。
     */
    public function getMyFollowList($myUserId,$targetUserId) {
        $this->recursive = 0;
        $this->displayField = 'follow_user_id';
        return $this->find('first', array(
            'conditions' => array(
                'user_id' => $myUserId,
                'follow_user_id' => $targetUserId
            ),
            "fields" => array("Follow.id")
        ));

    }

}

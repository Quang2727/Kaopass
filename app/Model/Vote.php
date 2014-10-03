<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 * @property User $User
 * @property Target $Target
 */
class Vote extends AppModel {

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
    
    public function getVoteUpData($params) {
        return $this->find('all', array(
            'conditions' => array(
                'Vote.related_user_id' => $params["related_user_id"],
                "Vote.vote_up > " => 0
            ),
            'recursive' => -1
        ));
    }

    public function countAllGroupUserId() {
        $this->virtualFields = array('count' => 'count(user_id)');
        return $this->find('list', array(
            "fields" => array("user_id", "count"),
            "conditions" => array(
                "created <" => date("Y-m-d", strtotime("now")),
                "vote_down" => VOTE_NONE
            ),
            "group" => "user_id"
        ));
    }
    
    public function countTermGroupUserId($days) {
        $this->virtualFields = array('count' => 'count(user_id)');
        return $this->find('list', array(
            "fields" => array("user_id", "count"),
            "conditions" => array(
                "created <" => date("Y-m-d", strtotime("now")),
                "created >=" => date("Y-m-d", strtotime("-$days days")),
                "vote_down" => VOTE_NONE
            ),
            "group" => "user_id"
        ));        
    }

    public function getCountByUserId($params) {
        return $this->find('count' ,array(
            'conditions' => array(
                'user_id' => $params['user_id']
            )
        ));
    }

    public function getVoteList($userId, $replyIds) {
        if (!$userId || !$replyIds) {
            return array();
        }
        return $this->find('list', array(
            'fields' => array(
                'Vote.vote_up',
                'Vote.vote_down',
                'Vote.target_id',
            ),
            'conditions' => array(
                'Vote.user_id' => $userId,
                'Vote.target_id' => $replyIds,
                'Vote.post_type' => 1,
            ),
        ));
    }
}

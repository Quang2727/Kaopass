<?php

App::uses('AppModel', 'Model');

/**
 * Reply Model
 *
 * @property Question $Question
 * @property User $User
 * @property ReplyComment $ReplyComment
 */
class Reply extends AppModel {

    public function createValidate() {
        $validate1 = array(
            'body' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("A01110_ERR_MSG003")
                ),
                'rule2' => array(
                    'rule' => array('maxLength', 10000),
                    'message' => __("A01110_ERR_MSG004", 10000)
                ),
            ),
        );
        $this->validate = $validate1;
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

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ReplyComment' => array(
            'className' => 'ReplyComment',
            'foreignKey' => 'reply_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Vote' => array(
            'className' => 'Vote',
            'foreignKey' => 'target_id',
            'dependent' => true,
            'conditions' => array(
                'post_type' => TARGET_TYPE_REPLY
            ),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );

    /**
     * Update data info before saving
     *
     * @author Mai Nhut Tan
     * @since 2013/10/03
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        return true;
    }

    /**
     * アソシエーション全解除
     */
    public function unbindAllAsociation() {
        $this->unBindModel(array(
            'hasMany' => array(
                "ReplyComment",
                "Vote"
            )
        ));
    }

    /**
     * get reply count
     *
     */
    public function getReplyCountByQuestionId($question_id) {
        if (is_null($question_id) === true || is_numeric($question_id) === false) return false;
        return $this->find('count', array(
            'conditions' => array(
                'question_id' => $question_id,
            ),
        ));
    }
    public function getReplyCountByQuestionIds($question_id_list) {
        $holder = implode(",", array_fill(0, count($question_id_list), "?") );

        $sql = "SELECT Reply.question_id as Reply__question_id, count(Reply.question_id) as Reply__counter FROM replies AS Reply WHERE Reply.question_id IN ({$holder}) group by Reply.question_id";
        return $result = $this->query($sql, $question_id_list);
    }

    /**
     * 論理的にデータを削除する。
     */
    public function deleteOnService($params) {
        if($this->deleteOnServiceById($params) === false &&
           $this->deleteOnServiceByQuetionId($params) === false) {
            return false;
        }
        return true;
    }    
    
    /**
     * 主キーを条件に論理的にデータを削除する。
     */
    public function deleteOnServiceById($params) {
        if(!isset($params["id"]) || empty($params["id"])) {
            return false;
        }        
        return $this->updateAll(
            array('display_flag' => FLAG_OFF,'delete_flag' => FLAG_DELETED),
            array('id =' => $params["id"])
        );
    }    

    /**
     * 質問IDを条件に論理的にデータを削除する。
     */
    public function deleteOnServiceByQuetionId($params) {
        if(!isset($params["question_id"]) || empty($params["question_id"])) {
            return false;
        }        
        return $this->updateAll(
            array('display_flag' => FLAG_OFF,'delete_flag' => FLAG_DELETED),
            array('question_id =' => $params["question_id"])
        );
    }

    public function getNoReplyUser($params) {
        $this->recursive = 1;
        return $this->find("first", array(
            'conditions' => array(
                "Reply.id" => $params["id"],
                "Reply.user_id <>" => $params["user_id"]
            ),
            'fields' => array(
                "Reply.user_id",
                "Reply.question_id"
            )
        ));
    }

    public function countAllGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(Reply.user_id)');
        return $this->find('list', array(
            "fields" => array("Reply.user_id", "Reply.count"),
            "conditions" => array(
                "Reply.created <" => date("Y-m-d", strtotime("now")),
                "Reply.display_flag" => FLAG_ON,
                "Reply.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("Reply.user_id"),
            "order" => array("Reply.user_id asc")
        ));
    }
    
    public function countTermGroupUserId($days) {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(Reply.user_id)');
        return $this->find('list', array(
            "fields" => array("Reply.user_id", "Reply.count"),
            "conditions" => array(
                "Reply.created <" => date("Y-m-d", strtotime("now")),
                "Reply.created >=" => date("Y-m-d", strtotime("-$days days")),
                "Reply.display_flag" => FLAG_ON,
                "Reply.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("Reply.user_id"),
            "order" => array("Reply.user_id asc")
        ));        
    }    
    
    public function countAllBestAnswerGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(Reply.user_id)');
#        $this->bindModel(array(
#            'hasMany' => array(
#                'ClipQuestion' => array(
#                    "table" => "clip_questions",
#                    "className" => "ClipQuestion",
##                    "type" => "inner",
#                    "dependent" => true,
#                    "foreignKey" => false,
##                    //"fields" => array(),
#                    "conditions" => array(
#                        "Reply.question_id = ClipQuestion.question_id",
#                        "ClipQuestion.delete_flag" => FLAG_NOT_DELETED,
#                    )
#                )
#            )
#        ));
        return $this->find('list', array(
        "fields" => array("Reply.user_id", "Reply.count"),
            'joins' => array(
                array(
                    'table' => 'clip_questions',
                    'alias' => 'ClipQuestion',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array(
                        'Reply.question_id = ClipQuestion.question_id',
                        'ClipQuestion.delete_flag' => FLAG_NOT_DELETED
                    )
                )
            ),
            "conditions" => array(
                "Reply.created <" => date("Y-m-d", strtotime("now")),
                "Reply.best_answer_flag" => REPLY_BEST_ANSWER,
                "Reply.display_flag" => FLAG_ON,
                "Reply.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("Reply.id ,Reply.user_id"),
            "order" => array("Reply.user_id asc")
        ));
    }

    public function countTermBestAnswerGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(Reply.user_id)');
        return $this->find('list', array(
            "fields" => array("Reply.user_id", "Reply.count"),
            'joins' => array(
                array(
                    'table' => 'clip_questions',
                    'alias' => 'ClipQuestion',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array(
                        'Reply.question_id = ClipQuestion.question_id',
                        'ClipQuestion.delete_flag' => FLAG_NOT_DELETED
                    )
                )
            ),
            "conditions" => array(
                "Reply.created <" => date("Y-m-d", strtotime("now")),
                "Reply.best_answer_flag" => REPLY_BEST_ANSWER,
                "Reply.display_flag" => FLAG_ON,
                "Reply.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("Reply.user_id"),
            "order" => array("Reply.user_id asc")
        ));
    }

    public function getNotBestAnswerUser($params) {
        $this->bindModel(array(
            'hasOne' => array(
                'User' => array(
                    "table" => "users",
                    "className" => "User",
                    "type" => "inner",
                    "dependent" => true,
                    "foreignKey" => false,
                    "conditions" => array(
                        'User.id = Reply.user_id',
                        'User.delete_flag' => FLAG_NOT_DELETED 
                    )
                )
            )
        ));
        return $this->find("all", array(
            "fields" => array("Reply.*","User.*"),
            "conditions" => array(
                "Reply.question_id" => $params["question_id"],
                "Reply.best_answer_flag" => REPLY_NOT_BEST_ANSWER,
                "Reply.display_flag" => FLAG_ON,
                "Reply.delete_flag" => FLAG_NOT_DELETED
            )
        ));
    }

    /**
     * 該当する解答の質問IDと質問タイトルを抽出。
     * @param array $ids replies.id
     * @return array([replies.id] => array([questions.id] => [questions.title]))
     */
    public function getQuestionTitles($ids) {
        return $this->find('list', array(
            'fields' => array(
                'Question.id',
                'Question.title',
                'Reply.id',
            ),
            'conditions' => array(
                'Reply.id' => $ids,
                'Reply.display_flag' => FLAG_ON,
                'Reply.delete_flag' => FLAG_NOT_DELETED,
            ),
            'joins' => array(
                array(
                    'table' => 'questions',
                    'alias' => 'Question',
                    'type' => 'left',
                    'conditions' => array(
                        'Reply.question_id = Question.id',
                        'Question.display_flag' => FLAG_ON,
                        'Question.delete_flag' => FLAG_NOT_DELETED,
                    ),
                ),
            ),
        ));
    }

    /**
     * 質問詳細に出力する回答および回答のコメントを抽出。
     * @param int $question_id questions.id
     * @param int $sort_type 回答ソート順
     * @return array 回答データの配列
     */
    public function getQuestionReplies($question_id, $sort_type = SORT_TYPE_NEW) {
        $tmp_replies = $this->getReplies($question_id, $sort_type);

        $replyUserIds = array();
        $userIds = array();
        foreach ($tmp_replies as $row) {
            $replyUserIds[] = $row['Reply']['user_id'];
            $userIds[] = $row['Reply']['user_id'];
            foreach ($row['ReplyComment'] as $line) {
                $userIds[] = $line['user_id'];
            }
        }
        $replyUserIds = array_unique($replyUserIds);
        $userIds = array_unique($userIds);

        $User = ClassRegistry::init('User');
        $User->unbindAllAsociation();
        $tmp_dataUser = $User->find('all', array(
            'fields' => array(
                'User.id',
                'User.display_name',
                'User.delete_flag',
                'User.photo',
                'User.user_type',
            ),
            'conditions' => array(
                'User.id' => $userIds,
            ),
        ));
        $dataUser = array();
        foreach ($tmp_dataUser as $row) {
            $dataUser[$row['User']['id']] = $row['User'];
        }
        unset($tmp_dataUser);

        $UserCount = ClassRegistry::init('UserCount');
        $dataUserCount = $UserCount->getCountAsNumOfTypeByUserIds(array('user_ids' => $userIds));

        $UserVoteScore = ClassRegistry::init('UserVoteScore');
        $dataUserVoteScore = $UserVoteScore->find('list', array(
            'fields' => array(
                'UserVoteScore.user_id',
                'UserVoteScore.total',
            ),
            'conditions' => array(
                'UserVoteScore.user_id' => $replyUserIds,
                'UserVoteScore.type' => VOTE_SCORE,
                'UserVoteScore.term' => TOTAL_ALL,
            ),
        ));

        $replies = array();
        foreach ($tmp_replies as $row) {
            $line = array();

            $user_id = $row['Reply']['user_id'];
            $line['Reply'] = $row['Reply'];
            $line['User'] = $dataUser[$user_id];
            $line['User']['UserCount'] = $dataUserCount[$user_id];
            $line['User']['UserVoteScore']['total'] = isset($dataUserVoteScore[$user_id]) ? $dataUserVoteScore[$user_id] : '';

            $total_count = count($row['ReplyComment']);
            if (0 < $total_count) {
                $line['Comments']['total_count'] = $total_count;
                $line['Comments']['list'] = $row['ReplyComment'];
                for ($i = $total_count - 1; $i >= 0; --$i) {
                    $user_id = $line['Comments']['list'][$i]['user_id'];
                    $line['Comments']['list'][$i]['User'] = $dataUser[$user_id];
                    $line['Comments']['list'][$i]['UserCount'] = $dataUserCount[$user_id];
                }
            }

            $replies[] = $line;
        }
        return $replies;
    }

    public function getReplies($question_id, $sort_type = SORT_TYPE_NEW) {
        $this->unBindModel(array(
            'hasMany' => array(
                'Vote',
            ),
        ));

        $order = array();
        $order[] = 'Reply.best_answer_flag DESC';
        switch ($sort_type) {
            case SORT_TYPE_OLD:
                $order[] = 'Reply.created ASC';
                break;
            case SORT_TYPE_LARGER_PLUS:
                $order[] = 'v.vote_up DESC NULLS LAST';
                break;
            default:
                $order[] = 'Reply.created DESC';
        }
        $order[] = 'Reply.id ASC';

        $dbo = $this->getDataSource();
        $subQuery = $dbo->buildStatement(
            array(
                'table' => 'votes',
                'alias' => 'Vote',
                'fields' => array(
                    '"Vote"."target_id"',
                    'SUM("Vote"."vote_up") AS vote_up',
                ),
                'conditions' => array(
                    '"Vote"."post_type"' => 1,
                ),
                'group' => '"Vote"."target_id"',
            ), $this
        );

        $this->hasMany['ReplyComment']['fields'] = array(
            'id', 'comment', 'comment_str', 'user_id', 'created', 'modified'
        );
        $this->hasMany['ReplyComment']['conditions'] = array(
            'display_flag' => FLAG_ON,
            'delete_flag' => FLAG_NOT_DELETED
        );
        $this->hasMany['ReplyComment']['order'] = array(
            'created ASC'
        );

        return $this->find('all', array(
            'fields' => array(
                'Reply.id',
                'Reply.user_id',
                'Reply.body',
                'Reply.best_answer_flag',
                'Reply.created',
                'Reply.modified',
                'Reply.body_str',
                'CASE WHEN "v"."vote_up" IS NULL THEN 0 ELSE "v"."vote_up" END AS "Reply__vote_up"',
            ),
            'joins' => array(
                array(
                    'table' => "({$subQuery})",
                    'alias' => 'v',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Reply.id = v.target_id',
                    ),
                ),
            ),
            'conditions' => array(
                'Reply.question_id' => $question_id,
                'Reply.display_flag' => FLAG_ON,
                'Reply.delete_flag' => FLAG_NOT_DELETED,
            ),
            'order' => $order,
        ));
    }

    public function getDataByUserId($userId,$order = array('Reply.modified' => 'DESC')) {
        return $this->find('all' ,array(
            'conditions' => array(
                'Reply.user_id' => $userId,
                'Reply.delete_flag' => FLAG_NOT_DELETED,
                'Reply.display_flag' => FLAG_ON
            ),
            'order' => $order
        ));
    }
}

<?php
App::uses('AppModel', 'Model');
/**
 * ClipQuestion Model
 *
 * @property User $User
 * @property Question $Question
 */
class ClipQuestion extends AppModel {

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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Question' => array(
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'conditions' => '',
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
                "User",
                "Question"
            )
        ));
    }

    public function countAllGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(ClipQuestion.user_id)');
        return $this->find('list', array(
            "fields" => array("ClipQuestion.user_id", "count"),
            "conditions" => array(
                "ClipQuestion.created <" => date("Y-m-d", strtotime("now")),
                "ClipQuestion.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("ClipQuestion.user_id"),
            "order" => array("ClipQuestion.user_id asc")
        ));
    }

    public function countTermGroupUserId($days) {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(ClipQuestion.user_id)');
        return $this->find('list', array(
            "fields" => array("ClipQuestion.user_id", "ClipQuestion.count"),
            "conditions" => array(
                "ClipQuestion.created <" => date("Y-m-d", strtotime("now")),
                "ClipQuestion.created >=" => date("Y-m-d", strtotime("-$days days")),
                "ClipQuestion.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("ClipQuestion.user_id"),
            "order" => array("ClipQuestion.user_id asc")
        ));
    }

    public function getClipCountByUserId($params) {
        return $this->find('count' ,array(
            'conditions' => array(
                'ClipQuestion.user_id' => $params['user_id']
            )
        ));
    }

    public function getShowClipByUserid($userId,$order = array('ClipQuestion.modified' => 'DESC')) {
        $this->unbindModel(array(
            'belongsTo' => array('User'),
        ));
        return array(
                $this->_getShowClipList($userId,$order),
                $this->_getShowClipCount($userId)
               );
    }

    protected function _getShowClipList($userId,$order) {
        return $this->find('all' ,array(
            'conditions' => array(
                'ClipQuestion.user_id' => $userId,
                'Question.delete_flag' => FLAG_NOT_DELETED,
                'Question.display_flag' => FLAG_ON
            ),
            'order' => $order,
            'limit' => LIMIT_QUESTION
        ));
    }

    protected function _getShowClipCount($userId) {
        return $this->find('count' ,array(
            'conditions' => array(
                'ClipQuestion.user_id' => $userId,
                'Question.delete_flag' => FLAG_NOT_DELETED,
                'Question.display_flag' => FLAG_ON
            ),
        ));
    }
    
    public function getClippedCountByUserId($params) {
        return $this->find('count' ,array(
            'conditions' => array(
                'Question.user_id' => $params['user_id']
            ),
#            'joins' => array(
#                array(
#                    'table' => 'questions',
#                    'alias' => 'Question',
#                    'type' => 'INNER',
#                    'conditions' => array(
#                        'Question.id = ClipQuestion.question_id'
#                    ),
#                ),
#            ),            
        ));
    }

    public function getClippedByTerm($days) {
        $this->unbindAllAsociation();
        $this->bindModel(array(
            "hasOne" => array(
                "Question" => array(
                    "table" => "questions",
                    "className" => "Question",
                    "type" => "inner",
                    "dependent" => true,
                    "foreignKey" => false,
                    "conditions" => array(
                        'Question.id = ClipQuestion.question_id',
                        "Question.display_flag" => FLAG_ON,
                        "Question.delete_flag" => FLAG_NOT_DELETED
                    )
                )
            )
        ));
        return $this->find("all", array(
            "conditions" => array(
                "ClipQuestion.created <" => date("Y-m-d H:i", strtotime("now")),
                "ClipQuestion.created >=" => date("Y-m-d H:i", strtotime("-$days days")),
                "ClipQuestion.delete_flag" => FLAG_NOT_DELETED 
            ),
            "fields" => array("Question.*"),
            "group" => array("Question.id"),
            "order" => array("Question.id asc")
        ));
    }

    public function countAllGroupQuestionId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(ClipQuestion.question_id)');
        return $this->find('list', array(
            "fields" => array("ClipQuestion.question_id", "count"),
            "conditions" => array(
                "ClipQuestion.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("ClipQuestion.question_id"),
            "order" => array("ClipQuestion.question_id asc")
        ));
    }

    /**
     * 条件に該当するクリップしたユーザ情報を抽出。
     * @param int/array $clipQuestionsQuestionId questions.id
     * @param int $clipQuestionsUserId 除外するusers.id
     */
    public function getClipUsers($clipQuestionsQuestionId, $clipQuestionsUserId = null) {
        $conditions = array(
            'ClipQuestion.question_id' => $clipQuestionsQuestionId,
            'ClipQuestion.delete_flag' => FLAG_NOT_DELETED,
        );
        if (0 < $clipQuestionsUserId) {
            $conditions['ClipQuestion.user_id <>'] = $clipQuestionsUserId;
        }

        return $this->find('all', array(
            'conditions' => $conditions,
        ));
    }
}

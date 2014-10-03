<?php
App::uses('AppModel', 'Model');
/**
 * QuestionCount Model
 *
 * @property QuestionCount $Question
 */
class QuestionCount extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $name = 'QuestionCount';
    public $useTable = 'question_count';

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

    public function updateCounter($question_id, $group, $up_flg = true) {

        if(empty($question_id) || empty($group)) {
            return false;
        }

        try {
            $data = $this->find('first', array(
                'conditions' => array(
                    'QuestionCount.question_id' => $question_id,
                    'QuestionCount.count_group' => $group,
                 ),
                 'fields' => array('QuestionCount.id', 'QuestionCount.counter')
            ));

            $value = $up_flg ? 1 : -1;
            
            $this->create();
            if(!empty($data)) {
                $conditions = array(
                    'id' => $data['QuestionCount']['id'],
                    'counter' => $data['QuestionCount']['counter'] + $value,
                    'question_id' => $question_id,
                    'count_group' => $group                    
                );
            } else {
                $conditions = array(
                    'counter' => 1,
                    'question_id' => $question_id,
                    'count_group' => $group
                );
            }
            $this->save($conditions);
        } catch(Exception $e) {
            $this->log('questionpvcount error = '.$e->getMessage(), LOG_ERROR);
        }
    }

    public function resetCounter($question_id, $group) {

        if(empty($question_id) || empty($group)) {
            return false;
        }

        try {
            $data = $this->find('first', array(
                'conditions' => array(
                    'QuestionCount.question_id' => $question_id,
                    'QuestionCount.count_group' => $group,
                 ),
                 'fields' => array('QuestionCount.id', 'QuestionCount.counter')
            ));

            if(!empty($data)) {
                $conditions = array(
                    'id' => $data['QuestionCount']['id'],
                    'counter' => 0,
                );
                $this->save($conditions);                
            }
        } catch(Exception $e) {
            $this->log('questionpvcount error = '.$e->getMessage(), LOG_ERROR);
        }
    }
    
    public function getQuestionCountIdByQID($question_id_list) {
        $holder = implode(",", array_fill(0, count($question_id_list), "?") );
        $conditions = array('question_id in ' => $question_id_list, 'count_group' => COUNT_GROUP_REPLY);
        $fields = array('id', 'question_id');
        return $this->find('all', array('conditions' => $conditions, 'fields' => $fields));
    }

    /**
     *
     * @param array(int) id
     * @param array $db_result
     * @return array
     */
    public function increaseQuestionCount($question_id=NULL, $type, $count=1) {
        if (is_null($question_id) === true || is_numeric($question_id) === false) return false;

        try {
            $data = $this->find('first', array(
                'conditions' => array(
                    'QuestionCount.question_id' => $question_id,
                    'QuestionCount.count_group' => $type,
                 ),
                 'fields' => array('QuestionCount.id', 'QuestionCount.counter')
            ));
            if(!empty($data)) {
                $this->create();
                $conditions = array(
                    'id' => $data['QuestionCount']['id'],
                    'counter' => $data['QuestionCount']['counter'] + $count,
                );
            } else {
                $conditions = array('QuestionCount.question_id' => $question_id, 'QuestionCount.count_group' => $type, 'QuestionCount.counter' => 1);
            }
            $result = $this->save($conditions);
        } catch(Exception $e) {
            $this->log('question_count update error = '.$e->getMessage(), LOG_ERROR);
        }
    }
    
    public function getByQuestionIdList($params) {
        return $this->find('list',array(
            'conditions' => array(
                'question_id' => $params['question_ids'],
                'count_group' => array(GROUP_REPLY_QUESTION_NUM, GROUP_CLIP_QUESTION_NUM),
            ),
            'fields' => array('QuestionCount.count_group','QuestionCount.counter','QuestionCount.question_id')
        ));        
    }
}

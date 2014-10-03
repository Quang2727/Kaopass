<?php
App::uses('AppModel', 'Model');
/**
 * QuestionPvCount Model
 *
 * @property Question $Question
 */
class QuestionPvCount extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'question_pv_count';

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

    public function updateCounter($question_id) {

        if(empty($question_id)) {
            return false;
        }

        try {
            $data = $this->find('first', array(
                'conditions' => array(
                    'QuestionPvCount.question_id' => $question_id),
                    'fields' => array('QuestionPvCount.id', 'QuestionPvCount.pv_counter')
            ));

            if(!empty($data)) {
                $conditions = array(
                    'id' => $data['QuestionPvCount']['id'],
                    'pv_counter' => $data['QuestionPvCount']['pv_counter'] + 1,
                );
            } else {
                $conditions = array(
                    'pv_counter' => 1,
                    'question_id' => $question_id
                );
            }
            $this->save($conditions,false);
        } catch(Exception $e) {
            $this->log('questionpvcount error = '.$e->getMessage(), LOG_ERROR);
        }
    }

    public function getQuestionPvCount($question_id) {
        return $this->find('all', array(
                   'conditions' => array('question_id' => $question_id)
               ));
    }

    /**
     * 各質問ごとのPV数を取得する
     */
    public function getSumByQuestionId($question_id_list) {
        return $this->find('all', array(
            'fields' => array('SUM(QuestionPvCount.pv_counter) as sum','QuestionPvCount.question_id'),
            'conditions' => array('QuestionPvCount.question_id' => $question_id_list),
            'group' => array('QuestionPvCount.question_id')
        ));
    }
}

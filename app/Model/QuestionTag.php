<?php

App::uses('AppModel', 'Model');

/**
 * QuestionTag Model
 *
 * @property Question $Question
 * @property Tag $Tag
 */
class QuestionTag extends AppModel {

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
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Tag' => array(
            'className' => 'Tag',
            'foreignKey' => 'tag_id',
            'conditions' => '',
//            'fields' => array('Tag.id', 'Tag.name'),
            'order' => ''
        )
    );

    public function getQuestionTags($question_id) {
        return $this->find('all', array(
                   'conditions' => array('QuestionTag.question_id' => $question_id)
               ));
    }
}

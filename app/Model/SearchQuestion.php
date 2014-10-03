<?php

App::uses('AppModel', 'Model');

/**
 * SearchQuestion Model
 *
 */
class SearchQuestion extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }

    /**
     * Build search condition array for search function
     *
     * @param string/SearchTerm $query
     * @param array $options
     * @return array
     * @author Mai Nhut Tan
     * @since 2013/09/26
     */
    public function buildSearchCondition($query, $options = array()) {
        if (is_string($query)) {
            App::import('Model', 'SearchTerm');
            $query = new SearchTerm($query);
        }

        $dbo = $this->getDatasource();

        //initial options
        $conditions = $fields = $order = array();

        //default search options
        if(!empty($query->scopes)) {
            foreach($query->scopes as $value) {
                $orCondition[] = array('SearchQuestion.search_body ilike' => '%' . $this->escapeLike($value) . '%');
            }
            $conditions['OR'] = $orCondition;
        }
        $order = array(
            'SearchQuestion.modified' => 'desc'
        );

        //extract tag filter
        if ($query->tag_filter) {
            $conditions['QuestionTag.tag_id'] = $query->tag_filter;
        }

        $options = array_merge_recursive(compact('conditions', 'fields', 'order'), $options);

        if(isset($options["conditions"]['QuestionTag.tag_id'])) {
                if(!empty($options["conditions"]['QuestionTag.tag_id'])) {
                $options['joins'] = array(
                        array(
                        'table' => 'question_tags',
                            'alias' => 'QuestionTag',
                            'type' => 'inner',
                            'conditions' => array(
                                'SearchQuestion.question_id = QuestionTag.question_id',
                                'QuestionTag.tag_id in ('.  implode(',', $options["conditions"]['QuestionTag.tag_id']).')',
                            )
                        )
                    );
                }
                unset($options["conditions"]['QuestionTag.tag_id']);
            }        
        return $options;
    }

    /**
     * LIKE句で利用する文字列をエスケープする
     * 
     * @param string $str 検索に用いる文字列
     * @return string エスケープ後の文字列
     */
    public function escapeLike($str) {
        $pattern = array('\\', '%', '_');
        foreach ($pattern as $p) {
            $str = str_replace($p, '\\' . $p, $str);
        }
        return $str;
    }
}

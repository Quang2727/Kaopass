<?php

App::uses('AppModel', 'Model');

/**
 * QuestionComment Model
 *
 * @property Question $Question
 * @property User $User
 */
class QuestionComment extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    public function createValidate() {

        $validate1 = array(
            'comment' => array(
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
     * Update data info before saving
     *
     * @author Mai Nhut Tan
     * @since 2013/10/03
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        //escape HTML tags
        if (isset($this->data['QuestionComment']['comment'])) {
            $body = $this->data['QuestionComment']['comment'];
            $this->data['QuestionComment']['comment'] = $body;
        }

        return true;
    }
    
    public function getReleaseComment($params) {
        $condition_params = array(
            "conditions" => array(
                "question_id" => $params['question_id'],
                'display_flag' => FLAG_ON,
                "delete_flag" => FLAG_NOT_DELETED
            ),
            "fields" => array("id","comment", "comment_str","user_id","created","modified"),
            "order" => array("created asc"),
        );

        if(isset($params["limit"]) && $params["limit"] > 0) {
            $condition_params["limit"] = $params["limit"];
            if(isset($params["offset"]) && $params["offset"] > 0) {
                $condition_params["offset"] = $params["offset"];
            }            
        }
        
        return $this->find(
            'all',
            $condition_params
        );
    }
    
    public function getReleaseCommentOfTotalCount($params) {
        $offset = 0;
        if(isset($params["offset"]) && $params["offset"] > 0) {
            $offset = $params["offset"];
        }
        return $this->find(
            'count',
            array(
                "conditions" => array(
                    "question_id" => $params['question_id'],
                    'display_flag' => FLAG_ON,
                    "delete_flag" => FLAG_NOT_DELETED
                )
            )
        );
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
}

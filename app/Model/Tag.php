<?php

App::uses('AppModel', 'Model');
App::import('Utility', 'Sanitize');

/**
 * Tag Model
 *
 * @property TagCategory $TagCategory
 */
class Tag extends AppModel {

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

    public function createValidate($id = null) {

        $validate1 = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("name not null ")
                ),
                'rule2' => array(
                    'rule' => array('checkExistName', $id),
                    'message' => __("name not null ")
                ),
            ),
            'explain' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("explain not null")
                ),
            ),
        );
        $this->validate = $validate1;
        return $this->validates($this->data);
    }

    function checkExistName($check, $id) {
        $name = $this->data['Tag']['name'];
        if (!empty($id))
            $data = $this->find("first", array(
                "conditions" => array(
                    "Tag.delete_flag" => FLAG_NOT_DELETED,
                    "Tag.name" => $name,
                    "Tag.id <>" => $id
                ),
                'fields' => array(
                    'Tag.id'
                ),
            ));
        else {
            $data = $this->find("first", array(
                "conditions" => array(
                    "Tag.delete_flag" => FLAG_NOT_DELETED,
                    "Tag.name" => $name,
                ),
                'fields' => array(
                    'Tag.id'
                ),
            ));
        }
        if (!empty($data))
            return false;
        return true;
    }

    /**
     * Update data before saving
     *
     * @author Mai Nhut Tan
     * @since 2013/09/16
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        //lowercase tag name before saving
        if (!empty($this->data['Tag']['name'])) {
            $this->data['Tag']['name'] = strtolower(trim($this->data['Tag']['name']));
        }
    }

    /**
     * 次の形式でtagsテーブルより情報抽出。
     * array(
     *     [id] => [name],
     * )
     * 
     * @param array $userTagIdList tags.idの配列
     * @return array 条件に該当するデータ
     */
    public function getTagNameByTagIdList($userTagIdList) {
        return $this->find('list', array(
            'conditions' => array(
                'Tag.id' => array_unique($userTagIdList),
                'Tag.delete_flag' => FLAG_NOT_DELETED,
            ),
        ));
    }

    public function getMyTags($params) {
        return $this->find("all", array(
            'fields' => array(
                'Tag.id',
                'Tag.name',
                'Tag.explain',
                'Tag.question_counter',
            ),            
            'conditions' => array(
                'Tag.id' => $params['user_tags'],
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => array(
                    'Tag.name'
                ),
            'recursive' => -1,
        ));
    }

    public function getMyTagsByNames($tag_names) {
        return $this->find("all", array(
            'fields' => array(
                'Tag.id',
                'Tag.name',
                'Tag.explain',
                'Tag.question_counter',
            ),
            'conditions' => array(
                'Tag.name' => $tag_names,
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => array(
                    'Tag.id'
                ),
            'recursive' => -1,
        ));
    }

    public function getMyTagsByQuestionId($questionId) {
        return $this->find("all", array(
            'conditions' => array(
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            'fields' => array('Tag.*','QuestionTag.question_id'),
            'order' => array(
                    'Tag.id'
                ),
            "joins" => array(
                array(
                   'type' => 'INNER',
                   'table' => 'question_tags',
                   'alias' => 'QuestionTag',
                    'conditions' => array(
                        'Tag.id = QuestionTag.tag_id',
                        'QuestionTag.question_id' => $questionId,
                    ),
                ),
           ),
        ));
    }

    public function getTagListWithQuestionCount($tag_name=null, $cache=false){        
        return $this->find('all', $this->setConditionsForTagList($tag_name, $cache));
    }

    /**
     * タグ一覧を生成するための条件を返す。
     */
    public function setConditionsForTagList($tag_name=null, $cache=false){        
        $options = array(
            'conditions' => array(
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => array(
                'Tag.question_counter DESC NULLS LAST',
                'Tag.name',
            )
        );        
        
        if(!is_null($tag_name)){
            $tag_name = str_replace(
                array('%', '_'),
                array('\%', '\_'),
                Sanitize::clean($tag_name)
            );
            $options['conditions']['Tag.name ILIKE'] = '%'.$tag_name.'%' ;
        }
        
        if($cache){
            $options['cache'] = array(
                'duration' => 15 * 60
            );
        }

        return $options;
    }    
    
    /**
     * counterをインクリメント、デクリメントするファンクション
     * 
     * @param $tag_id タグID 
     * @param $flg インクリメントフラグ(true：インクリメント、false：デクリメント)
     **/
    public function updateCounter($tags_id, $up_flg = true) {

        if(empty($tags_id)) {
            return false;
        }

        try {
            $data = $this->find('all', array(
                'conditions' => array(
                    'Tag.id' => $tags_id,
                 ),
                 'fields' => array('Tag.id', 'Tag.question_counter')
            ));

            $up_count = $up_flg ? 1 : -1;
            if(!empty($data)) {
                foreach($data as $key => $value) {
                    $this->create();
                    $conditions = array(
                        'id' => $value['Tag']['id'],
                        'question_counter' => $value['Tag']['question_counter'] + $up_count,
                    );
                    $this->save($conditions);
                }
            }
        } catch(Exception $e) {
            $this->log('tag update error = '.$e->getMessage(), LOG_ERROR);
        }    
    }
    
    public function getByName($params) {
        return $this->find('first', array(
            'conditions' => array(
                'LOWER(Tag.name)' => $params['name'],
                'Tag.delete_flag' => FLAG_NOT_DELETED
            )
        ));        
    }

    public function getPopularTag() {
        //後々テーブルから取得する仕様になるためとりあえずここに設置
        $pt = array('PHP', 'Java', 'Ruby', 'Perl', 'Apache', 'MySQL', 'JavaScript', 'jQuery', 'CakePHP', 'Struts', 'Symfony', 'Spring');

        $popTags = $this->find('all', array(
            "conditions" => array(
                'Tag.name' => $pt,
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            "fields" => array(
                "Tag.id",
                "Tag.name",
                "Tag.explain",
                "Tag.question_counter",
            ),
            "order" => "Tag.question_counter DESC"
        ));

        return $popTags;
    }
}

<?php

App::uses('AppModel', 'Model');

/**
 * Question Model
 *
 * @property User $User
 * @property QuestionComment $QuestionComment
 * @property QuestionPvCount $QuestionPvCount
 * @property QuestionTag $QuestionTag
 * @property Reply $Reply
 * @property ReplyRequest $ReplyRequest
 */
class Question extends AppModel {

    public $actsAs = array('CommonValidates');
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    /** モデル側で並び順をチェックし正しい値を格納する */
    public $type;

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'title' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A01120_ERR_MSG002')
                ),
                'length' => array(
                    'rule' => array('maxLength', 100),
                    'message' => __('A01120_ERR_MSG003', 100)
                )
            ),
            'tags' => array(
                'length' => array(
                    'rule' => array(
                        'checkTags'
                    ),
                    'required' => false
                )
            ),
            'body' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A01120_ERR_MSG004'),
                    'last' => true
                ),
                'range' => array(
                    'rule' => array('rangeLength', 50, 10000),
                    'message' => __('A01120_ERR_MSG008', 50, 10000)
                ),
            ),
            'accepted_flag' => array(
                'digit' => array(
                    'rule'    => '/^[01]$/',
                ),
            ),
            'display_flag' => array(
                'numeric' => array(
                    'rule' => array(
                        'numeric'
                    )
                )
            ),
            'delete_flag' => array(
                'numeric' => array(
                    'rule' => array(
                        'numeric'
                    ),
                )
            ),
        );
    }

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array('User' => array('className' => 'User', 'foreignKey' => 'user_id', 'conditions' => '', 'fields' => '', 'order' => ''));

    /**
     * hasOne associations
     *
     * @var array
     */
    public $hasOne = array(
        'QuestionPvCount' => array(
            'className' => 'QuestionPvCount',
            'foreignKey' => 'question_id',
            'dependent' => true,
//            'conditions' => '',
//            'fields' => array(
//                'QuestionPvCount.id',
//                'QuestionPvCount.pv_counter'
//            ),
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ClipQuestion' => array(
            'className' => 'ClipQuestion',
            'foreignKey' => 'question_id',
            'dependent' => true,
            'conditions' => "",
            'fields' => array(
                'ClipQuestion.id',
                'ClipQuestion.user_id'
            ),
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'QuestionTag' => array(
            'className' => 'QuestionTag',
            'foreignKey' => 'question_id',
            'dependent' => true,
            'fields' => array(
                'QuestionTag.id',
                'QuestionTag.tag_id'
            ),
        ),
        'Reply' => array(
            'className' => 'Reply',
            'foreignKey' => 'question_id',
            'dependent' => true,
            'conditions' => array(
                'Reply.delete_flag' => FLAG_NOT_DELETED,
                'Reply.display_flag' => FLAG_ON
            ),
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
                'post_type' => TARGET_TYPE_QUESTION
            ),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
/*
        'ReplyRequest' => array(
            'className' => 'ReplyRequest',
            'foreignKey' => 'question_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
*/
    );

    /**
     * Custom validation for tags
     *
     * @param array $fields
     * @return mixed
     * @author Mai Nhut Tan
     * @since 2013/09/19
     */
    public function checkTags($fields) {
        $fields = array_values($fields);
        if (empty($fields[0]))
            return __('A01120_ERR_MSG009');

        if(count(explode(',', $fields['0'])) > 5) {
            return __('A01120_ERR_MSG010');
        }

        //get user tags
        $form_tags = preg_split('/[,\0100]+/', $fields[0], -1, PREG_SPLIT_NO_EMPTY);

        //get instance of Tag model
        $TagModel = $this->QuestionTag->Tag;

        //get valid tags
        $valid_tags = $TagModel->find('list', array(
            'conditions' => array(
                'Tag.name' => $form_tags,
                'Tag.delete_flag' => FLAG_NOT_DELETED
            )
        ));
        $valid_tags = array_values($valid_tags);

        $invalid_tags = array_values(array_diff($form_tags, $valid_tags));

        if (empty($invalid_tags)) {
            return true;
        } else {
            return __('A01120_ERR_MSG006', implode(',', $invalid_tags));
        }
    }

    /**
     * SaveNotification
     *
     * @param question_id , type , user_id
     * @return void
     * @author Nguyen Ngoc Thai
     * @since 2013/09/06
     * @see http://goo.gl/l4rjjG
     */
    function SaveNotification($question_id, $type, $user_id) {
        APP::import("Model", array(
            "Notification",
            "Reply",
            "ClipQuestion"
        ));
        $this->Notification = new Notification();
        $this->Reply = new Reply();
        $this->ClipQuestion = new ClipQuestion();
        if ($type == NOTIFICATION_REPLY) {
            $this->recursive = -1;

            // get list questions
            $question = $this->find("first", array(
                'conditions' => array(
                    'Question.id' => $question_id,
                    'Question.user_id <>' => $user_id
                ),
                'fields' => array(
                    "Question.user_id"
                )
            ));

            // check empty question
            if (!empty($question))
                $dataAlert[] = array(
                    'user_id' => $user_id,
                    'type' => $type,
                    'message_id' => $question_id,
                    'related_user_id' => $question['Question']['user_id'],
                    'read_flag' => NOT_READ_NOTIFICATION
                );

            $listId = array(
                $user_id,
                $question['Question']['user_id']
            );
            $this->Reply->recursive = -1;

            // get list data Reply
            $reply = $this->Reply->find("all", array(
                'conditions' => array(
                    "Reply.question_id" => $question_id,
                    "Reply.user_id <>" => $listId,
                    'Reply.delete_flag' => FLAG_NOT_DELETED,
                    'Reply.display_flag' => FLAG_ON
                ),
                'fields' => array(
                    "Reply.user_id"
                )
            ));
            foreach ($reply as $value) {
                $listId[] = $value['Reply']['user_id'];
                $dataAlert[] = array(
                    'user_id' => $user_id,
                    'type' => NOTIFICATION_OTHER_REPLY,
                    'message_id' => $question_id,
                    'related_user_id' => $value['Reply']['user_id'],
                    'read_flag' => NOT_READ_NOTIFICATION
                );
            }

            // get list data ClipQuestion
            $clips = $this->ClipQuestion->find("all", array(
                'conditions' => array(
                    "ClipQuestion.question_id" => $question_id,
                    "ClipQuestion.delete_flag" => FLAG_NOT_DELETED,
                    "ClipQuestion.user_id <>" => $listId
                ),
                'fields' => array(
                    "ClipQuestion.user_id"
                )
            ));
            foreach ($clips as $value) {
                $listId[] = $value['ClipQuestion']['user_id'];
                $dataAlert[] = array(
                    'user_id' => $user_id,
                    'type' => NOTIFICATION_REPLY_CLIP_QUESTION,
                    'message_id' => $question_id,
                    'related_user_id' => $value['ClipQuestion']['user_id'],
                    'read_flag' => NOT_READ_NOTIFICATION
                );
            }
            if (!empty($dataAlert))
                return $this->Notification->saveMany($dataAlert);
        }
        return true;
    }

    /**
     * Get tag data and put tags to field Question.tags
     *
     * @author Mai Nhut Tan
     * @since 2013/09/23
     */
    public function afterFind($results, $primary = false) {
        foreach ($results as $i => &$question) {
            if (!is_array($question))
                continue;

            if (!empty($question['QuestionTag'])) {
                $tag_ids = array();
                if (isset($question['QuestionTag']['tag_id']) === true) {
                    $tag_ids[] = $question['QuestionTag']['tag_id'];
                } else {
                    foreach ($question['QuestionTag'] as $tag_info) {
                        $tag_ids[] = $tag_info['tag_id'];
                    }
                }
                $tag_list = $this->QuestionTag->Tag->find('list', array(
                    'conditions' => array(
                        'id' => $tag_ids
                    )
                ));
                $tag_list = array_values($tag_list);
                $question['Question']['tags'] = implode(',', $tag_list);
            }
        }

        return $results;
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
        $to_search = '%' . $query->text . '%';
        $escaped_string = $dbo->value($to_search);

        //initial options
        $conditions = $fields = $order = array();

        //default search options
        $conditions['OR'] = array(
            'LOWER(Question.title) LIKE' => strtolower($to_search),
            'Question.body LIKE' => $to_search
        );
        $order = array(
            'Question.modified' => 'DESC'
        );

        /**
         * Build find conditions
         *
         * For complex phrase (scopes > 1) -> use fulltext search
         * For simple phrase (scopes < 2) -> use normal search
         * Tags will be added as additional condition
         */
        $scope_lenght = count($query->scopes);
        if ($scope_lenght > 1) {

            /**
             * @see http://www.slideshare.net/billkarwin/full-text-search-in-postgresql
             * @todo override search options
             * @author
             * @since
             *
             * // Example:
             *
             * $fields = array(
             *     'ts_rank_cd(title, query) AS Question__title_rank',
             *     'ts_rank_cd(body, query) AS Question__body_rank'
             *  );
             *
             *  $conditions = array(
             *      "to_tsvector(title || ' ' || body) @@ to_tsquery({$escaped_string})"
             *  );
             *
             *  $order = array(
             *      '(Question__title_rank + Question__title_rank)' => 'DESC'
             *  );
             */
        }

        //extract tag filter
        if (count($query->tags) > 0) {
            //get tag id list
            $this->QuestionTag->Tag->displayField = 'id';
            $tag_filter = $this->QuestionTag->Tag->find('list', array(
                'conditions' => array(
                    'Tag.name' => array_map('strtolower', $query->tags)
                )
            ));
            //get question id with provided tags
            $this->QuestionTag->displayField = 'question_id';
            $question_filter = $this->QuestionTag->find('list', array(
                'conditions' => array(
                    'tag_id' => $tag_filter
                )
            ));

            //push filter
            if (empty($question_filter)) {
                $conditions['Question.id'] = -1;
            } else if (isset($conditions['Question.id'])) {
                $conditions['Question.id'] = array_merge((array) $conditions['Question.id'], $question_filter);
            } else {
                $conditions['Question.id'] = $question_filter;
            }
        }

        $options = array_merge_recursive(compact('conditions', 'fields', 'order'), $options);

        return $options;
    }

    /**
     * _bindModelQuestionInfo
     *
     * @param
     * @return void
     * @author Ngoc thai
     * @since 2013/09/06
     * @todo implement code
     * @see http://goo.gl/l4rjjG
     */
    function _bindModelQuestionList() {
        $this->bindModel(array(
            'hasMany' => array(
                'Reply' => array(
                    'className' => 'Reply',
                    'foreignKey' => 'question_id',
                    'fields' => array(
                        'Reply.id',
                        'Reply.best_answer_flag'
                    ),
                    'conditions' => array(
                        'Reply.delete_flag' => FLAG_NOT_DELETED,
                        'Reply.display_flag' => FLAG_ON
                    ),
                ),
                'ClipQuestion' => array(
                    'className' => 'ClipQuestion',
                    'foreignKey' => 'question_id',
                    'conditions' => array(
                        'ClipQuestion.user_id' => $this->LoginUser['id'],
                        'ClipQuestion.delete_flag' => FLAG_NOT_DELETED
                    )
                )
            )
        ));
        $this->Reply->unBindModel(array(
            'belongsTo' => array(
                'User'
            )
        ));
        $this->Vote->unBindModel(array(
            'belongsTo' => array(
                'User'
            )
        ));
    }

    /**
     * アソシエーション全解除
     */
    public function unbindAllAsociation() {
        $this->unBindModel(array(
            'belongsTo' => array(
                "User",
            ),
            'hasOne' => array(
                "QuestionPvCount",
            ),
            'hasMany' => array(
                "ClipQuestion",
                "QuestionTag",
                "Reply",
                "Vote"
            )
        ));
    }

    /**
     * sort  list question
     *
     *
     * @method _sortData
     * @param condition, order
     * @return void
     * @author Nguyen Ngoc Thai
     * @since 2013/09/13
     */
    function sortData($conditionQuestion, $order, $listUserTag=array(), $controller=null) {
        switch ($order) {
            case 'btnMytag':
            case SORT_MYTAG:
                $order = SORT_MYTAG;
                break;
            case 'btnNew':
            case SORT_DATE:
                $order = SORT_DATE;
                break;
            case 'btnUnresolved':
            case SORT_NOT_DONE:
                $order = SORT_NOT_DONE;
                break;
            case 'btnResolved':
            case SORT_DONE:
                $order = SORT_DONE;
                break;
            case 'btnUnanswered':
            case SORT_UNANSWERED:
                $order = SORT_UNANSWERED;
                break;
            default:
                $order = SORT_VIEW;
        }
        $this->type = $order;

        if ($order == SORT_DATE) { // order by date
            $defaultOrder = "Question.modified DESC";
            $result = $this->getDataQuestion($conditionQuestion, $defaultOrder, NULL, LIMIT_QUESTION);

            $this->unBindModel(array(
                'hasMany' => array(
                    "ClipQuestion",
                    "QuestionTag",
                    "Reply",
                    "Vote"
                )
            ));
        } elseif ($order == SORT_VIEW) { // order by count view
            $created_field = 'CASE WHEN "r".created IS NULL THEN "Question".created ELSE "r".created END';

            if ($controller && 'Questions' ===  $controller->name) {
                $conditionQuestion[] = '(' . $created_field . ') > (CURRENT_TIMESTAMP - INTERVAL \'' . CONDITION_SORT_VIEW_EXPIRE . '\')';
            }

            $defaultOrder = 'weight DESC';

            $this->recursive = 2;
            // bind and unbind model Question
            $this->unbindModeQuestion();
            $this->unBindModel(array(
                'hasMany' => array(
                    "ClipQuestion",
                    "QuestionTag",
                    "Reply",
                    "Vote"
                )
            ));
            $this->Vote->unBindModel(array(
                'belongsTo' => array(
                    'User'
                )
            ));

            $result = $this->getDataQuestion($conditionQuestion, $defaultOrder, NULL , LIMIT_QUESTION);

            $weight  = '(';
            $weight .= WEIGHT_SORT_VIEW_CREATED . ' * COALESCE(EXTRACT(epoch FROM CURRENT_TIMESTAMP - (' . $created_field . '))/(24*60*60), 0) +';
            $weight .= WEIGHT_SORT_VIEW_PV . ' * COALESCE("QuestionPvCount".pv_counter, 0) +';
            $weight .= WEIGHT_SORT_VIEW_CLIP . ' * COALESCE("QuestionCountClip".counter, 0) +';
            $weight .= WEIGHT_SORT_VIEW_ANSWER . ' * COALESCE("QuestionCountAnswer".counter, 0) +';
            $weight .= WEIGHT_SORT_VIEW_VOTE . ' * COALESCE(r.vote_up, 0)';
            $weight .= ') AS weight';

            $result['fields'][] = $weight;

            $dbo = $this->getDataSource();
            $subQuery1 = $dbo->buildStatement(
                array(
                    'table' => 'votes',
                    'alias' => 'vv',
                    'fields' => array(
                        'vv.target_id',
                        'SUM(vv.vote_up) AS vote_up',
                    ),
                    'conditions' => array(
                        'vv.post_type' => 1,
                    ),
                    'group' => 'vv.target_id',
                ), $this
            );
            $subQuery2 = $dbo->buildStatement(
                array(
                    'table' => 'replies',
                    'alias' => 'rr',
                    'fields' => array(
                        'rr.question_id',
                        'SUM(v.vote_up) AS vote_up',
                        'MAX(rr.created) AS created',
                    ),
                    'joins' => array(
                        array(
                            'table' => "({$subQuery1})",
                            'alias' => 'v',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'rr.id = v.target_id',
                            ),
                        ),
                    ),
                    'conditions' => array(
                        'rr.display_flag' => FLAG_ON,
                        'rr.delete_flag' => FLAG_NOT_DELETED,
                    ),
                    'group' => 'rr.question_id',
                ), $this
            );

            $result['joins'] = array(
                array(
                    'table' => "({$subQuery2})",
                    'alias' => 'r',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Question.id = r.question_id',
                    ),
                ),
                array(
                    'table' => 'question_count',
                    'alias' => 'QuestionCountClip',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Question.id = QuestionCountClip.question_id',
                        'QuestionCountClip.count_group' => 1,
                    ),
                ),
                array(
                    'table' => 'question_count',
                    'alias' => 'QuestionCountAnswer',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Question.id = QuestionCountAnswer.question_id',
                        'QuestionCountAnswer.count_group' => 4,
                    ),
                ),
            );

            $result['group'] = null;
        } elseif ($order == SORT_MYTAG) { // order by count tag
            $defaultOrder = "Question.modified DESC";

            $this->recursive = 2;
            // bind and unbind model Question
            $this->unbindModeQuestion();
            $this->unBindModel(array(
                'hasMany' => array(
                    "ClipQuestion",
                    "QuestionTag",
                    "Reply",
                )
            ));
//            $this->bindModel(array(
//                'belongsTo' => array(
//                    'QuestionTag' => array(
//                        'className' => 'QuestionTag',
//                        'foreignKey' => false,
//                        'conditions' => array(
//                            'QuestionTag.tag_id' => $conditionQuestion['QuestionTag.tag_id'],
//                            'Question.id=QuestionTag.question_id',
//                        )
//                    )
//                )
//            ));
            //sort data
            $result = $this->getDataQuestion($conditionQuestion, $defaultOrder, NULL, LIMIT_QUESTION);
            if(!empty($listUserTag)) {
                $result['joins'] = array(
                array('table' => 'question_tags',
                    'alias' => 'QuestionTag',
                    'type' => 'inner',
                    'conditions' => array(
                        'Question.id = QuestionTag.question_id',
                        "QuestionTag.tag_id in (".  implode(',', array_values($listUserTag)).")"
                    )
                ));
            }

        } elseif (SORT_UNANSWERED === $order) {
            $defaultOrder = 'Question.created ASC';
            $conditionQuestion['QuestionCount.counter'] = NULL;

            $result = $this->getDataQuestion($conditionQuestion, $defaultOrder, NULL, LIMIT_QUESTION);
            $result['joins'] = array(array(
                'table' => 'question_count',
                'alias' => 'QuestionCount',
                'type' => 'LEFT',
                'conditions' => array(
                    'Question.id = QuestionCount.question_id',
                    'QuestionCount.count_group' => 4,
                ),
            ));

            $this->unBindModel(array(
                'hasMany' => array(
                    "ClipQuestion",
                    "QuestionTag",
                    "Reply",
                    "Vote"
                )
            ));
        } else { // order by done and not done
            // bind and unbind model Question
            $this->unbindModeQuestion();
            $this->unBindModel(array(
                'hasMany' => array(
                    "ClipQuestion",
                    "QuestionTag",
                    "Vote",
                    "Reply"
                )
            ));

            if ($order == SORT_NOT_DONE) { // order by not done
                $conditionQuestion['Question.accepted_flag'] = UNACCEPTED_QUESTION;
                $defaultOrder = "Question.modified desc";
            } else { // order by  done
                $conditionQuestion['Question.accepted_flag'] = ACCEPTED_QUESTION;
                $defaultOrder = "Question.modified desc";
            }
            $result = $this->getDataQuestion($conditionQuestion, $defaultOrder, NULL, LIMIT_QUESTION);
        }
        return $result;
    }

    /**
     * set data top user
     * ????
     *
     * @param int
     * @return void
     * @author
     * @since 2013/09/06
     * @todo implement code
     * @see http://goo.gl/l4rjjG
     */
    function setDataTopUser() {

        // bind and unbind model User
        $this->User->unbindModel(array(
            'hasOne' => array(
                'UserInfo'
            ),
            'hasMany' => array(
                'ClipQuestion',
                'Notification',
                'QuestionComment',
                'Question',
                'Reply',
                'ReplyComment',
                'ReplyRequest',
                'UserTag',
                'Follow',
                'Follower',
                'Vote',
                'UserMedal',
                'SnsInfo'
            )
        ));

        $users = $this->User->find('all', array(
            'conditions' => array(
                'User.delete_flag' => FLAG_NOT_DELETED,
                'User.id <>' => $this->LoginUser['id']
            ),
            'order' => 'week.total DESC',
            'limit' => LIMIT_USER,
            'fields' => array(
                'User.*',
                'UserVoteScore.total',
            ),
            'joins' => array(
                array(
                    'table' => 'user_vote_scores',
                    'alias' => 'UserVoteScore',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = UserVoteScore.user_id',
                        'UserVoteScore.type' => VOTE_SCORE,
                        'UserVoteScore.term' => TOTAL_ALL,
                    ),
                ),
                array(
                    'table' => 'user_vote_scores',
                    'alias' => 'week',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = week.user_id',
                        'week.type' => VOTE_SCORE,
                        'week.term' => TOTAL_WEEK,
                    ),
                ),
            ),
        ));

        return $users;
    }

    /**
     * set data Top User
     *
     *
     * @method setDataTopTag
     * @param
     * @return void
     * @author Nguyen Ngoc Thai
     * @since 2013/09/13
     */
    function setDataTopTag() {
        $tags = $this->QuestionTag->Tag->find('all', array(
            'conditions' => array(
                'Tag.delete_flag' => FLAG_NOT_DELETED
            ),
            'order' => 'random()',
            'limit' => PAGING,
        ));
        return $tags;
    }

    /**
     * get list question
     *
     *
     * @method getDataQuestion
     * @param condition, order by , listId
     * @return void
     * @author Nguyen Ngoc Thai
     * @since 2013/09/13
     */
    function getDataQuestion($conditionQuestion, $order, $listId = NULL, $paging = NULL, $action = NULL) {
        if (!isset($conditionQuestion['Question.display_flag'])) {
            $conditionQuestion['Question.display_flag'] = FLAG_ON;
        }
        if (!isset($conditionQuestion['Question.delete_flag'])) {
            $conditionQuestion['Question.delete_flag'] = FLAG_NOT_DELETED;
        }

        $this->recursive = 2;
        // bind and unbind model Question
        $this->unbindModeQuestion();
        $this->bindModelQuestionList();
        if (!empty($listId)) {
            $listId = array_keys($listId);
            $conditionQuestion['Question.id'] = $listId;
        }
        $this->unbindModel(array('hasMany' => array('QuestionTag')));
        if (isset($conditionQuestion['QuestionTag.tag_id'])) {
            $this->bindModel(array(
                'belongsTo' => array(
                    'QuestionTag' => array(
                        'className' => 'QuestionTag',
                        'foreignKey' => false,
                        'conditions' => array(
                            'QuestionTag.tag_id' => $conditionQuestion['QuestionTag.tag_id'],
                            'Question.id=QuestionTag.question_id',
                        )
                    )
                )
            ));
            unset($conditionQuestion['QuestionTag.tag_id']);
        }

        //paging question
        $setting = array(
            'conditions' => $conditionQuestion,
            'fields' => array(
                'Question.*'
            ),
            'group' => 'Question.id',
            'order' => $order
        );
        if (!empty($listId)) {
            if (empty($action)) {
                $order = "idx('{" . implode(", ", $listId) . "}'::int[],Question.id)";
                $setting['order'] = $order;
            }
        }
        if (!empty($action)) {
            $this->recursive = 2;

            // bind and unbind Model Question
            $this->bindModel(array(
                'hasMany' => array(
                    'Reply' => array(
                        'className' => 'Reply',
                        'foreignKey' => 'question_id',
                        "fields" => array(
                            "Reply.id",
                            "Reply.best_answer_flag"
                        ),
                        'conditions' => array(
                            'Reply.delete_flag' => FLAG_NOT_DELETED,
                            'Reply.display_flag' => FLAG_ON
                        ),
                    )
                )
            ));
            $this->unBindModel(array(
                'belongsTo' => array(
                    'User'
                ),
                'hasOne' => array(
                    'QuestionPvCount'
                ),
                'hasMany' => array(
                    'ClipQuestion',
                    'QuestionComment',
                )
            ));
            $question = $this->find("all", array(
                "conditions" => $conditionQuestion,
                'limit' => $paging,
                'order' => "random()",
                'group' => 'Question.id',
                'fields' => array(
                    'Question.id',
                    'Question.user_id',
                    'Question.accepted_flag',
                    'Question.title',
                    'Question.body',
                    'Question.body_str',
                    'Question.modified'
                )
            ));
            if (!$question) {
                return array();
            }

            $user_ids = array();
            foreach ($question as $item) {
                $user_ids[] = $item['Question']['user_id'];
                $question_ids[] = $item['Question']['id'];
            }

            $this->user = ClassRegistry::init('User');
            $this->userCount = ClassRegistry::init('UserCount');
            $this->questionTag = ClassRegistry::init('QuestionTag');
            $this->questionPvCount = ClassRegistry::init('QuestionPvCount');

            $this->user->unBindModel(array(
                'hasOne' => array(
                    'UserInfo'
                ),
                'hasMany' => array(
                    'Notification',
                    'QuestionComment',
                    'Question',
                    'ClipQuestion',
                    'Reply',
                    'ReplyComment',
                    'ReplyRequest',
                    'UserTag',
                    'Vote',
                    'Follow',
                    'Follower',
                    'UserMedal',
                    'SnsInfo',
                )
            ));
            $questionTagData = Hash::combine($this->questionTag->getQuestionTags($question_ids), '{n}.QuestionTag.question_id','{n}');
            $questionPvCountData = Hash::combine($this->questionPvCount->findAllByQuestionId($question_ids), '{n}.QuestionPvCount.question_id','{n}');

            $result = array();
            if(!empty($user_ids)) {
                $userData = $this->user->getReleaseUsers(array("user_ids" => $user_ids));
                $userCountData = $this->userCount->getCountAsNumOfTypeByUserIds(array("user_ids" => $user_ids));

                $result = array();
                foreach($question as $value) {
                    $user_id = $value["Question"]["user_id"];
                    $question_id = $value["Question"]["id"];
                    $addUserCount["UserCount"] = array();
                    if(isset($userCountData[$user_id])) {
                        $addUserCount['UserCount'] = $userCountData[$user_id];
                    }
                    $user["User"] = array();
                    if(isset($userData[$user_id]["User"])) {
                        $user["User"] = array_merge($userData[$user_id]["User"],$addUserCount);
                    }
                    $tag = array();
                    if(isset($questionTagData[$question_id])) {
                        $tag = $questionTagData[$question_id];
                    }
                    $pv = array();
                    if(isset($questionPvCountData[$question_id])) {
                        $pv = $questionPvCountData[$question_id];
                    }
                    $result[] = array_merge($value,$user,$tag,$pv);
                }
            }

            return $result;
        }
        if (!empty($paging))
            $setting['limit'] = $paging;
        return $setting;
    }

    /**
     * _bindModelQuestionInfo
     *
     * @param
     * @return void
     * @author Ngoc thai
     * @since 2013/09/06
     * @todo implement code
     * @see http://goo.gl/l4rjjG
     */
    function bindModelQuestionList() {
        $this->bindModel(array(
            'hasMany' => array(
                'Reply' => array(
                    'className' => 'Reply',
                    'foreignKey' => 'question_id',
                    'fields' => array(
                        'Reply.id',
                        'Reply.best_answer_flag'
                    ),
                    'conditions' => array(
                        'Reply.delete_flag' => FLAG_NOT_DELETED,
                        'Reply.display_flag' => FLAG_ON
                    ),
                ),
                'ClipQuestion' => array(
                    'className' => 'ClipQuestion',
                    'foreignKey' => 'question_id',
                    'conditions' => array(
                        'ClipQuestion.user_id' => $this->LoginUser['id'],
                        'ClipQuestion.delete_flag' => FLAG_NOT_DELETED
                    )
                ),
                'QuestionPvCount' => array(
                    'className' => 'QuestionPvCount',
                    'foreignKey' => 'question_id',
                    'fields' => array(
                        'QuestionPvCount.id',
                        'QuestionPvCount.pv_counter'
                    )
                )
            )
        ));
        $this->Reply->unBindModel(array(
            'belongsTo' => array(
                'User'
            )
        ));
        $this->Vote->unBindModel(array(
            'belongsTo' => array(
                'User'
            )
        ));
    }

    /**
     * _bindModelQuestionInfo
     *
     * @param
     * @return void
     * @author Ngoc thai
     * @since 2013/09/06
     * @todo implement code
     * @see http://goo.gl/l4rjjG
     */
    function bindModelFeedList() {
        $this->bindModel(
                array('hasMany' => array(
                        'Reply' => array(
                            'className' => 'Reply',
                            'foreignKey' => 'question_id',
                            'fields' => array('Reply.id', 'Reply.best_answer_flag'),
                            'conditions' => array(
                                'Reply.delete_flag' => FLAG_NOT_DELETED,
                                'Reply.display_flag' => FLAG_ON
                            ),
                        ),
                        'ClipQuestion' => array(
                            'className' => 'ClipQuestion',
                            'foreignKey' => 'question_id',
                            'conditions' => array(
                                'ClipQuestion.user_id' => $this->LoginUser['id'],
                                'ClipQuestion.delete_flag' => FLAG_NOT_DELETED,
                            )
                        ),
                        'QuestionPvCount' => array(
                            'className' => 'QuestionPvCount',
                            'foreignKey' => 'question_id',
                            'fields' => array('QuestionPvCount.id', 'QuestionPvCount.pv_counter'),
                        ),
                    )
                )
        );
        $this->Reply->unBindModel(array(
            'belongsTo' => array('User')
        ));
        $this->Vote->unBindModel(array(
            'belongsTo' => array('User')
        ));
    }


    /**
     * unbind model question
     *
     *
     * @method unbindModeQuestion
     * @param
     * @return void
     * @author Nguyen Ngoc Thai
     *
     * @since 2013/09/13
     */
    function unbindModeQuestion() {
        $this->User->unBindModel(array(
            'hasMany' => array(
                'ClipQuestion',
                'Notification',
                'QuestionComment',
                'Question',
                'Reply',
                'ReplyComment',
                'ReplyRequest',
                'Follow',
                'Follower',
                'UserTag'
            )
        ));
        $this->ClipQuestion->unBindModel(array(
            'belongsTo' => array(
                'Question',
                "User"
            )
        ));
        $this->QuestionTag->unBindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));

        $this->Reply->unBindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->Reply->unBindModel(array(
            'hasMany' => array(
                'ReplyComment',
                'Vote'
            )
        ));
    }

    /**
     * Rebinding model relations for reducing queries
     *
     * @param string/SearchTerm $query
     * @param array $options
     * @return array
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    public function prepareSearch() {
        $this->User->unbindModel(array(
            'hasOne' => array(
                'UserInfo'
            ),
            'hasMany' => array(
                'ClipQuestion',
                'Notification',
                'QuestionComment',
                'Question',
                'Reply',
                'ReplyComment',
                'ReplyRequest',
                'UserTag',
                'Vote',
                'Follow',
                'Follower'
            )
        ));
        $this->QuestionComment = ClassRegistry::init('QuestionComment');
        $this->QuestionPvCount = ClassRegistry::init('QuestionPvCount');
        $this->ClipQuestion = ClassRegistry::init('ClipQuestion');
        $this->QuestionTag = ClassRegistry::init('QuestionTag');
        $this->ReplyRequest = ClassRegistry::init('ReplyRequest');
        $this->QuestionComment->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->QuestionPvCount->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->ClipQuestion->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->QuestionTag->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->Reply->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->ReplyRequest->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
        $this->Vote->unbindModel(array(
            'belongsTo' => array(
                'Question'
            )
        ));
/*
        $this->User->cacheQueries = true;
        $this->QuestionComment->cacheQueries = true;
        $this->QuestionPvCount->cacheQueries = true;
        $this->ClipQuestion->cacheQueries = true;
        $this->QuestionTag->cacheQueries = true;
        $this->Reply->cacheQueries = true;
        $this->ReplyRequest->cacheQueries = true;
        $this->Vote->cacheQueries = true;
*/

        $this->recursive = 2;
        $this->cacheQueries = true;
/*
        $this->unbindModel(array(
            'hasMany' => array(
                'ReplyRequest'
            )
        ));
*/
    }

    /**
     * 論理的にデータを削除する。
     */
    public function deleteOnService($params) {
        if(!isset($params["id"]) || empty($params["id"])) {
            return false;
        }
        return $this->updateAll(
            array('Question.display_flag' => FLAG_OFF,'Question.delete_flag' => FLAG_DELETED),
            array('Question.id =' => $params["id"])
        );
    }

    public function getOnService($params) {
        return $this->find("first", array(
            "conditions" => array(
                'Question.id' => $params["id"],
                'Question.display_flag' => FLAG_ON,
                'Question.delete_flag' => FLAG_NOT_DELETED
            )
        ));
    }

    public function getRelatedInfo($user, $sort = SORT_VIEW, $id = NULL, $action = NULL, $tag_id = null, $type = null, $listId = null, $title = null) {
        $this->LoginUser = $user;

        $this->UserTag = ClassRegistry::init('UserTag');

        $listUserTag = array();
        if (isset($user['id'])) {
            $listUserTag = $this->UserTag->getRandomByUserId(array('user_id' => $user['id']));
        }

        $tags = $this->setDataTopTag();
        $dataUser = $this->setDataTopUser();

        // set paramrter url
        $paramarter = array(
            "id" => $id,
            "tag_id" => $tag_id,
            "type" => $type,
            "sort" => $sort
        );

        // get list data follow
        $this->Follow = ClassRegistry::init('Follow');

        $dataFowllow = array();
        if (isset($user['id'])) {
            $dataFowllow = $this->Follow->getFollowUser(array('user_id' => $user['id']));
        }

        $data = array(
            "userTags" => $listUserTag,
            'follows' => $dataFowllow,
            'tags' => $tags,
            'users' => $dataUser,
            'paramarter' => $paramarter
        );
        return $data;
    }

    /**
     * 検索窓に表示する質問データを取得する。
     *
     * @param type $ids
     * @return type
     */
    public function getBoxData($ids) {
        return $this->find('all', array(
            'limit' => 10,
            'conditions' => array(
                'Question.display_flag' => FLAG_ON,
                'Question.delete_flag' => FLAG_NOT_DELETED,
                'Question.id' => $ids
            ),
            "order" => array(
                "Question.modified DESC"
            ),
            'recursive' => 1,
            'cache' => true
        ));
    }

    /**
     * ページ送りで使用されている bindを設定する。
     *
     * @param type $params
     */
    public function bindForSearchData($params) {
        $this->bindModel(array(
            'hasMany' => array(
                'ClipQuestion' => array(
                    'className' => 'ClipQuestion',
                    'foreignKey' => 'question_id',
                    'conditions' => array(
                        'ClipQuestion.user_id' => $params['user_id'],
                        'ClipQuestion.delete_flag' => FLAG_NOT_DELETED
                    )
                ),
            )
        ));
    }

    public function getJoinQuestionTagData($params) {
        return $this->find("list", array(
            'joins' => array(
                array(
                    'table' => 'question_tags',
                    'alias' => 'QuestionTag',
                    'type' => 'left',
                    'foreignKey' => false,
                    'conditions' => array(
                        'QuestionTag.question_id = Question.id'
                    )
                )
            ),
            "conditions" => array(
                "Question.display_flag" => FLAG_ON,
                "Question.delete_flag" => FLAG_NOT_DELETED,
                'QuestionTag.tag_id' => $params['tag_id']
            ),
            'fields' => array(
                'Question.id',
                'Question.id'
            )
        ));
    }

    /**
     * 回答数、クリップ数、タグ情報を設定する
     *
     * @param type $questions
     * @return type
     */
    public function setListData($questions) {
        $this->QuestionCount = ClassRegistry::init('QuestionCount');
        $this->QuestionPvCount = ClassRegistry::init('QuestionPvCount');
        $this->QuestionTag = ClassRegistry::init('QuestionTag');
        $this->Tag = ClassRegistry::init('Tag');


        $question_id_list = Hash::combine($questions, '{n}.Question.id', '{n}.Question.id');

        // 質問の知りたい数、回答数を取得
        $tag_list = array();
        if(!empty($question_id_list)) {
            $question_count_list = $this->QuestionCount->getByQuestionIdList(array('question_ids' => $question_id_list));
            $questionPvCountList = Hash::combine($this->QuestionPvCount->findAllByQuestionId($question_id_list), '{n}.QuestionPvCount.question_id', '{n}.QuestionPvCount');
        $this->unBindModel(array(
            'hasOne' => array(
                "QuestionPvCount",
            ),
            'hasMany' => array(
                "ClipQuestion",
                "QuestionTag",
                "Reply",
                "Vote"
            )
        ));
            $this->User->unbindModel(array(
                'hasOne' => array(
                    'UserInfo',
                ),
                'hasMany' => array(
                    'ClipQuestion',
                    'Notification',
                    'QuestionComment',
                    'Question',
                    'Reply',
                    'ReplyComment',
                    'ReplyRequest',
                    'UserTag',
                    'Follow',
                    'Follower',
                    'UserMedal',
                    'Vote',
                    'SnsInfo',
                )
            ));
            $user_list = Hash::combine($this->findAllById($question_id_list), '{n}.Question.id', '{n}.User');
            $question_tag_list = $this->Tag->getMyTagsByQuestionId($question_id_list);
            foreach($question_tag_list as $value) {
                $questionId = $value["QuestionTag"]["question_id"];
                $tag_list[$questionId][] = $value;
            }
        }

        $question_merge_list = array();
        foreach($questions as $value) {
            $user_id = (isset($value['User']['id']) && !empty($value['User']['id'])) ? $value['User']['id'] : '';
            $questionId = $value['Question']['id'];
            $value['QuestionTag'] = !empty($tag_list[$questionId]) ? $tag_list[$questionId] : array();
            $value['QuestionPvCount'] = !empty($questionPvCountList[$questionId]) ? $questionPvCountList[$questionId] : array();
            $value['User'] = $user_list[$questionId];
            $value['reply_num'] = isset($question_count_list[$questionId][GROUP_REPLY_QUESTION_NUM]) ? $question_count_list[$questionId][GROUP_REPLY_QUESTION_NUM] : 0;
            $value['clip_num'] = isset($question_count_list[$questionId][GROUP_CLIP_QUESTION_NUM]) ? $question_count_list[$questionId][GROUP_CLIP_QUESTION_NUM] : 0;
            $question_merge_list[] = $value;
        }

        return $question_merge_list;

    }

    public function countAllGroupUserId() {
        $this->unbindAllAsociation();
        $this->virtualFields = array('count' => 'count(Question.user_id)');
        return $this->find('list', array(
        "fields" => array("Question.user_id","Question.count"),
            "conditions" => array(
                "Question.created <" => date("Y-m-d", strtotime("now")),
                "Question.display_flag" => FLAG_ON,
                "Question.delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => array("Question.user_id"),
            "order" => array("Question.user_id asc")
        ));
    }

    public function countTermGroupUserId($days) {
        $this->unbindAllAsociation();
        return $this->find('list', array(
            "fields" => array("user_id", "count(user_id)"),
            "conditions" => array(
                "created <" => date("Y-m-d", strtotime("now")),
                "created >=" => date("Y-m-d", strtotime("-$day days")),
                "display_flag" => FLAG_ON,
                "delete_flag" => FLAG_NOT_DELETED
            ),
            "group" => "user_id",
            "order" => array("user_id asc")
        ));
    }

    public function getDataByUserId($userId,$order = array('Question.modified' => 'DESC')) {
        $this->unBindModel(array(
            'belongsTo' => array('User'),
            'hasOne' => array('QuestionPvCount'),
            'hasMany' => array(
                'Reply',
                'QuestionTag',
                'Vote'
            ),
        ));
        $conditions = array(
            'Question.user_id' => $userId,
            'Question.delete_flag' => FLAG_NOT_DELETED,
            'Question.display_flag' => FLAG_ON
        );
        return array(
                   $this->_getList($conditions,$order),
                   $this->_getCount($conditions)
               );
    }

    public function getDataByQuestionId($questionId,$order = array('Question.modified' => 'DESC')) {
        $this->unBindModel(array(
            'belongsTo' => array('User'),
            'hasOne' => array('QuestionPvCount'),
            'hasMany' => array(
                'Reply',
                'QuestionTag',
                'Vote'
            ),
        ));
        $conditions = array(
            'Question.id' => $questionId,
            'Question.delete_flag' => FLAG_NOT_DELETED,
            'Question.display_flag' => FLAG_ON
        );
        return array(
                   $this->_getList($conditions,$order),
                   $this->_getCount($conditions)
               );
    }

    protected function _getList($conditions,$order) {
        return $this->find('all' ,array(
            'conditions' => $conditions,
            'order' => $order,
            'limit' => LIMIT_QUESTION
        ));
    }

    protected function _getCount($conditions) {
        return $this->find('count' ,array(
            'conditions' => $conditions,
        ));
    }

    public function getAllQuestionByUserId($questionId,$order) {
        $this->unbindModeQuestion();
        $this->bindModelQuestionList();
        $this->recursive = 2;

        $this->unBindModel(array(
            'hasMany' => array('QuestionComment', 'ClipQuestion', 'QuestionTag', 'Reply', 'Vote', 'ReplyRequest'),
        ));
        $this->User->unbindModel(array(
            'hasOne' => array('UserInfo'),
            'hasMany' => array(
                'SnsInfo',
                'Vote',
                'UserMedal',
            )
        ));
        return $this->find('all', array(
            'conditions' => array(
                'Question.id' => $questionId,
                'Question.delete_flag' => FLAG_NOT_DELETED,
                'Question.display_flag' => FLAG_ON
            ),
            'order' => $order
        ));
    }

    public function getQuestionByUserId($userId,$id) {
        return $this->find("list", array(
            'fields' => array('id'),
            'conditions' => array(
                'Question.display_flag' => FLAG_ON,
                'Question.delete_flag' => FLAG_NOT_DELETED,
                'Question.id <>' => $id,
                "Question.user_id" => $userId
            )
        ));

    }

    public function getQuestionByTag($listIdTag,$id) {
        return $this->find("list", array(
                'joins' => array(
                    array(
                        'table' => 'question_tags',
                        'alias' => 'QuestionTag',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Question.id = QuestionTag.question_id'
                        )
                    )
                ),
                "conditions" => array(
                    "QuestionTag.tag_id" => $listIdTag,
                    "Question.display_flag" => FLAG_ON,
                    "Question.delete_flag" => FLAG_NOT_DELETED,
                    'Question.id <>' => $id,
                ),
                'order' => "random()",
                'fields' => array(
                    'id'
                ),
                'limit' => PAGING - 1
            ));
    }
}

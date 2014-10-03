<?php

App::uses('AppModel', 'Model');

/**
 * First Contact Check Model
 *
 * @property User $User
 * @property QuestionComment $QuestionComment
 * @property QuestionPvCount $QuestionPvCount
 * @property QuestionTag $QuestionTag
 * @property Reply $Reply
 * @property ReplyRequest $ReplyRequest
 */
class CheckFirstContact extends AppModel {
    
    public $actsAs = false;
    public $useTable = false;
    public $validate = array();

    // check sql
    private $__sql = <<< EOF
SELECT user_id  , 'replies' as kind 
FROM replies
WHERE user_id = ?
UNION
SELECT user_id  , 'reply_requests' as kind 
FROM reply_requests
WHERE user_id = ?
UNION
SELECT user_id , 'votes' as kind 
FROM votes
WHERE user_id = ?
UNION
SELECT user_id , 'questions' as kind 
FROM questions
WHERE user_id = ?
UNION
SELECT user_id , 'follows' as kind 
FROM follows
WHERE user_id = ?
GROUP BY user_id , kind
EOF;

    // the number 0f first contact kind
    private $__kind_num = 5;

    /**
     * constructor
     */
    public function __construct($id = false, $table = null, $ds = null) {}
    
    /**
     *  check first contact
     *
     *  @param user_id
     *  @param kind of first contact(1, 2, 3, 4, 5)
     *  @return boolean
     */
    public function checkAllFirstContact($user_id) {

        if(empty($user_id)) {
            return false;
        }

        $count = 0;

        try {
            $bindParam = array();
            for($i = 0; $i < FIRST_CONTACT_KIND_NUM; $i++) {
                $bindParam[] = $user_id;
            }
            // execute sql
            $res = $this->query($this->__sql, $bindParam);

            if(!empty($res) && is_array($res)) {
                // the number of kind
                $count = count($res);
            }
        } catch(Exception $e) {
            $this->log('DB select ERROR detail='.$e->getMessage(), 2);
        }

        return $count == FIRST_CONTACT_KIND_NUM ? true : false;
    }

    /**
     *  check first contact
     *
     *  @param user_id
     *  @param kind of first contact(1, 2, 3, 4, 5)
     *  @return boolean
     */
    public function checkFirstContact($user_id, $kind) {

        if(empty($user_id) || empty($kind)) {
            return false;
        }

        $isKindFirstContact = false;
        $isAllFirstContact = false;
        $count = 0;

        try {
            $bindParam = array();
            for($i = 0; $i < FIRST_CONTACT_KIND_NUM; $i++) {
                $bindParam[] = $user_id;
            }
            // execute sql
            $res = $this->query($this->__sql, $bindParam);

            if(!empty($res) && is_array($res)) {

                // the number of kind
                $count = count($res);

                if($count == $this->__kind_num) {
                    // ファーストコンタクト全5種類制覇の場合
                    $isAllFirstContact = true;
                }

                foreach($res as $key => $rec) {
                    if($rec[0]['kind'] == $kind) {
                        $isKindFirstContact = true;
                    }
                }
            }
        } catch(Exception $e) {
            $this->log('DB select ERROR detail='.$e->getMessage(), 2);
        }

        return array(
                   'isKindFirstContact' => $isKindFirstContact,
                   'isAllFirstContact' => $isAllFirstContact,
                   'count' => $count
               );
    }
}

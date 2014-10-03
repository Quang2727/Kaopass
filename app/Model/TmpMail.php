<?php

App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property ClipQuestion $ClipQuestion
 * @property Notification $Notification
 * @property QuestionComment $QuestionComment
 * @property Question $Question
 * @property Reply $Reply
 * @property ReplyComment $ReplyComment
 * @property ReplyRequest $ReplyRequest
 * @property SnsInfo $SnsInfo
 * @property UserInfo $UserInfo
 * @property UserTag $UserTag
 * @property Vote $Vote
 */
class TmpMail extends AppModel {

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
            'mail_address' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG003')
                ),
                'length' => array(
                    'rule' => 'email',
                    'message' => __('A03000_ERR_MSG005')
                ),
                'rule3' => array(
                    'rule' => array('maxLength', 45),
                    'message' => __('A03000_ERR_MSG004', 45)
                ),
            ),
        );
    }

    public function isReset($params) {
        $conditions["TmpMail.unique_key"] = $params['unique_key'];
        $conditions["TmpMail.available_flag"] = 1;
        $conditions["TmpMail.created >"] = date("Y-m-d H:i:s",strtotime("-2 hour"));

        $result = $this->find("first", array(
            "conditions" => $conditions,
            "fields" => "mail_address"
                ));

        if (empty($result)) {
            return false;
        }
        return $result;
    }

    public function findByUniqueKey($uniqueKey) {
        $conditions["TmpMail.unique_key"] = $uniqueKey;
        $conditions["TmpMail.created >"] = date("Y-m-d H:i:s",strtotime("-1 hour"));

        $result = $this->find("first", array(
            "conditions" => $conditions
                ));

        if (empty($result)) {
            return false;
        }
        return $result;
    }

    function checkExistEmailNoLogin($fields = array(), $id = null) {

        APP::import("Model", array(
            "User"
        ));
        $this->recursive = -1;
        $data = $this->data;
        if (!empty($data['TmpMail']['mail_address'])) {
            $conditions["User.mail_address"] = $data['TmpMail']['mail_address'];
            $this->User = new User();

            $user = $this->User->find("first", array(
                "conditions" => $conditions,
                "fields" => "id"
                    ));
            if (empty($user))
                return false;
        }
        return true;
    }

}

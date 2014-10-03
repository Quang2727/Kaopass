<?php

App::uses('AppModel', 'Model');

/**
 * Tmp User Model
 *
 */
class TmpUser extends AppModel {

    private $_nameRegex = '/^[a-zA-Z0-9\._\-]+$/';
    //private $_nameRegex = '/^[\w!#$%&\'*+\/=?^`{|}~-]+(?:\.[\w!#$%&\'*+\/=?^`{|}~-]+)*$/';

    /**
     * Enable soft deletion
     */
    public $actsAs = array('CommonValidates');

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
            'display_name' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG002')
                ),
                'regex' => array(
                    'rule'    => 'dispNameRegex',
                    'message' => __('A03000_ERR_MSG004', 15)
                ),
                'minlength' => array(
                    'rule' => array('minLength', 3),
                    'message' => __('AA03000_ERR_MSG004', 15)
                ),
                'length' => array(
                    'rule' => array('maxLength', 15),
                    'message' => __('A03000_ERR_MSG004', 15)
                ),
                'reserved' => array(
                    'rule'    => 'checkReservedName',
                    'message' => __('ユーザ名で入力頂いたワードは使用できません。')
                ),                
                'unique' => array(
                    'rule' => array(
                        'checkExistName'
                    ),
                    'message' => __('A03000_ERR_MSG026')
                ),
            ),
            'mail_address' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG003')
                ),
                'length' => array(
                    'rule' => 'email',
                    'message' => __('A03000_ERR_MSG005')
                ),
                'unique' => array(
                    'rule' => array(
                        'checkExistEmail'
                    ),
                    'message' => __('A03000_ERR_MSG006')
                ),
                'rule3' => array(
                    'rule' => array('maxLength', 45),
                    'message' => __('A03000_ERR_MSG004', 45)
                ),
            ),
            'password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG007')
                ),
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __("A03000_ERR_MSG014", 6, 20)
                ),
                'range' => array(
                    'rule' => array(
                        'checkRangePassword'
                    ),
                    'message' => __('A03000_ERR_MSG014', 6, 20)
                )                                                
            ),
            'repeat_password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A03000_ERR_MSG015')
                ),
                'match' => array(
                    'rule' => array(
                        'repeatPassword'
                    ),
                    'message' => __('A03000_ERR_MSG009')
                )                                
            ),
            'password_confirm' => array(
                'confirm' => array(
                    'rule' => array(
                        'confirmPassword'
                    ),
                    'message' => __('A03000_ERR_MSG010')
                )
            ),
        );
        
    }

    // ユーザー名は英数-_.で許可する
    function dispNameRegex($request_data) {
        return Validation::custom($request_data['display_name'],$this->_nameRegex);
    }
    
    // 登録禁止の表示名確認をする。（大小区別なし）
    function checkReservedName($request_data) {
        App::import('Model',array(
            'ReservedWord',
        ));
        $this->ReservedWord = new ReservedWord();
        $reservedWord = $this->ReservedWord->query('select id from reserved_words where word ilike ?',array($request_data['display_name']));
        if(!empty($reservedWord)) {
            return false;
        }

        return true;
    }

    // 会員新規登録時に表示名の重複確認を行う（大小区別なし）
    function checkExistName($fields=array()) {
        $data = $this->data;

        if(!empty($data['TmpUser']['display_name'])) {
            $user = $this->query('select id from users where display_name ilike ? ',array($data['TmpUser']['display_name']));
            if(!empty($user)) {
                return false;
            }
            if(isset($this->data['TmpUser']['id'])) {
                $user = $this->query('select id from tmp_users where display_name ilike ? and created > ? and available_flag = ? and id != ?',array($data['TmpUser']['display_name'],date("Y-m-d H:i:s",strtotime("-30 minutes")),1,$this->data['TmpUser']['id']));                
            } else {
                $user = $this->query('select id from tmp_users where display_name ilike ? and created > ? and available_flag = ? ',array($data['TmpUser']['display_name'],date("Y-m-d H:i:s",strtotime("-30 minutes")),1));
            }
            if(!empty($user)) {
                return false;
            }            
        }
        return true;
    }
    
    function checkExistEmail($fields = array()) {
        APP::import("Model", array(
            "User"
        ));
        $data = $this->data;
        if (!empty($data['TmpUser']['mail_address'])) {
            $conditions["User.mail_address"] = $data['TmpUser']['mail_address'];

            $this->User = new User();
            $user = $this->User->find("first", array(
                "conditions" => $conditions,
                "fields" => "id"
                    ));

            if (!empty($user))
                return false;
        }
        return true;
    }    

    /**
     * パスワードの確認
     * 入力フォームと登録時のバリデーションの文字数が異なるため、独自実装する
     * 
     * @param type $fields
     * @return boolean
     */
    function checkRangePassword($fields = array()) {
        if(isset($this->data["TmpUser"]["id"])) {
            return true;
        } else {
            return Validation::range(mb_strlen($this->data["TmpUser"]["password"]), 5, 21);
        }
    }    
    
    /**
     * Validate password mathch
     *
     * @author Mai Nhut Tan
     * @since 2013/09/12
     */
    function repeatPassword($fields = array()) {

        //force quit when changing password without retyping password
        if (!isset($this->data['TmpUser']['repeat_password']) || !isset($this->data['TmpUser']['password']))
            return false;

        return ($this->data['TmpUser']['password'] == $this->data['TmpUser']['repeat_password']);
    }
    
    public function getPreRegister($params) {
        return $this->find("first", array(
            "conditions" => array(
                "unique_key" => $params["unique_key"],
                "created >" => date("Y-m-d H:i:s",strtotime("-30 minutes")),
                "delete_flag" => FLAG_NOT_DELETED,
                "available_flag" => 1,
            )
        ));        
    }

    public function getNewMailAddress($params) {
        return $this->find("first", array(
            "conditions" => array(
                "unique_key" => $params["unique_key"],
                "user_id" => $params["user_id"],
                "created >" => date("Y-m-d H:i:s",strtotime("-60 minutes")),
                "delete_flag" => FLAG_NOT_DELETED,
                "available_flag" => 1,
            )
        ));        
    }    
    
    /**
     * Update data info before saving
     *
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        //lowercase email before saving
        if (!empty($this->data['TmpUser']['mail_address'])) {
            $this->data['TmpUser']['mail_address'] = strtolower(trim($this->data['TmpUser']['mail_address']));
        }

        //hash password before saving
        if (!empty($this->data['TmpUser']['password'])) {
            $this->data['TmpUser']['password'] = AuthComponent::password($this->data['TmpUser']['password']);
        } else {
            unset($this->data['TmpUser']['password']);
        }

        return true;
    }    
    
}

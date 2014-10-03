<?php

App::uses('AppModel', 'Model');

/**
 * Vote Model
 *
 * @property User $User
 * @property Target $Target
 */
class Admin extends AppModel {

    public function createValidate($id = null) {

        $validate1 = array(
            'display_name' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("display_name not null ")
                ),
            ),
            'username' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("username not null")
                ),
                'length' => array(
                    'rule' => array('between', 5, 40),
                    'allowEmpty' => true,
                    'message' => __('ki tu tu 5 --> 40')
                ),
                'ruel' => array(
                    'rule' => array('checkExistUser', $id),
                    'allowEmpty' => true,
                    'message' => __('username has been exisest')
                )
            ),
            'password' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("password not null")
                ),
                'length' => array(
                    'rule' => array('between', 6, 40),
                    'allowEmpty' => true,
                    'message' => __('ki tu tu 6 --> 40 ')
                )
            ),
        );
        $this->validate = $validate1;
        return $this->validates($this->data);
    }

     public function editValidate($id = null) {

        $validate1 = array(
            'password' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("password not null")
                ),
                'length' => array(
                    'rule' => array('between', 6, 40),
                    'allowEmpty' => true,
                    'message' => __('ki tu tu 6 --> 40 ')
                )
            ),
        );
        $this->validate = $validate1;
        return $this->validates($this->data);
    }
    function checkExistUser($check, $id = null) {
        $username = $this->data['Admin']['username'];
        if (!empty($id))
            $dataAdmin = $this->find("first", array(
                "conditions" => array(
                    "Admin.delete_flag" => FLAG_NOT_DELETED,
                    "Admin.username" => $username,
                    "Admin.id <>" => $id
                ),
                'fields' => array(
                    'Admin.id'
                ),
            ));
        else {
            $dataAdmin = $this->find("first", array(
                "conditions" => array(
                    "Admin.delete_flag" => FLAG_NOT_DELETED,
                    "Admin.username" => $username,
                ),
                'fields' => array(
                    'Admin.id'
                ),
            ));
        }
        if (!empty($dataAdmin))
            return false;
        return true;
    }

}

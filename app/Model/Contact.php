<?php

App::uses('AppModel', 'Model');

class Contact extends AppModel {

    public $useTable = false;

    public function createValidate() {
        $validate1 = array(
            'mail_address' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => __('A30000_ERR_MSG001')
                ),
                'length' => array(
                    'rule' => 'email',
                    'message' => __('A30000_ERR_MSG002')
                ),
                'rule2' => array(
                    'rule' => array('maxLength', 50),
                    'message' => __('A03000_ERR_MSG016', 50)
                ),
            ),
            'subject' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => __("A30000_ERR_MSG003")
                ),
            ),
            'title' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("A30000_ERR_MSG004")
                ),
                'rule2' => array(
                    'rule' => array('maxLength', 50),
                    'message' => __('A01120_ERR_MSG003', 50)
                ),
            ),
            'content' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => __("A30000_ERR_MSG005")
                ),
                'rule2' => array(
                    'rule' => array('maxLength', 1000),
                    'message' => __('A30000_ERR_MSG008', 1000)
                ),
            ),
            'file' => array(
                'rule' => '__checkValidateUploadFile',
                'message' => __('A03000_ERR_MSG007')
            ),
        );
        $this->validate = $validate1;
        return $this->validates($this->data);
    }

    /**
     * uplaod file check (extension and size)
     *
     * @data  input data
     */
    private function __checkValidateUploadFile($data) {
        if (empty($data['file']['name'])) {
            return true;
        }
        $mime = $data['file']['type'];

        // header file : 'pdf', 'doc', 'docx', 'xls2', 'xlsx', 'txt', 'jpg', 'gif', 'png'
        $array_header = array(
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "text/plain",
            "application/vnd.ms-excel",
            "text/html",
            "image/jpeg",
            "image/png",
            "image/gif"
        );
        if ($data['file']['size'] / 1024 > 2048) {
            return false;
        }
/*
        if (!in_array($mime, $array_header)) {
            return false;
        }
*/
        return true;
    }

}

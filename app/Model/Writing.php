<?php
App::uses('AppModel', 'Model');
/**
 * Writing Model
 *
 */
class Writing extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array();

    public function ValidateDate() {
        $validate = array(
            'start_time' => array(
                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => '開始日を入力してください。'
                ),
                'rule2' => array(
                    'rule' => array( 'date', 'ymd'),
                    'message' => '開始日を「YYYY-MM-DD」のフォーマットで入力してください。',
                )
            ),
            'end_time' => array(
                                'rule1' => array(
                    'rule' => 'notEmpty',
                    'message' => '終了日を入力してください。'
                ),
                'rule2' => array(
                    'rule' => array( 'date', 'ymd'),
                    'message' => '終了日を「YYYY-MM-DD」のフォーマットで入力してください。',
                ),
                'match' => array(
                    'rule' => array(
                        'compareDatetime'
                    ),
                    'message' => '終了日を開始日より最新の日付にしてください。'
                )
            )
        );
        $this->validate = $validate;
        $this->validates($this->data);
        return $this->validationErrors;
    }

    /**
     * Validate password mathch
     *
     * @author Mai Nhut Tan
     * @since 2013/09/12
     */
    public function compareDatetime($fields = array()) {
        if (strtotime($this->data['Writing']['end_time']) <= strtotime($this->data['Writing']['start_time'])) {
            return false;
        }
        return true;
    }


    public function updateStatus($id,$status=WRITING_MAKED_REPLY_STATUS) {
        if (is_numeric($id) === false) return false;
        $this->read(null,$id);
        $this->set(array(
            'status'   => WRITING_MAKED_REPLY_STATUS,
            'reply_id' => $this->import_reply_id,
            'modified' => 'now()',
        ));
        return $this->save();
    }
}

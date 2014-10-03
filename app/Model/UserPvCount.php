<?php

App::uses('AppModel', 'Model');

/**
 * Vote Model
 *
 * @property User $User
 * @property Target $Target
 */
class UserPvCount extends AppModel {

    public $useTable = 'user_pv_count';

    function updateCountUser($user_id) {
        $this->begin();
        APP::import("Model", array("UserPvCount"));
        $this->UserPvCount = new UserPvCount();
        $data = $this->find("first", array(
            "conditions" => array(
                "UserPvCount.date" => date("Y-m-d"),
                "UserPvCount.user_id" => $user_id,
            ),
            "fields" => array("UserPvCount.id", "UserPvCount.pv_counter")
                ));
        $this->create();
        if (!empty($data)) {
            $dataSave = array(
                "id" => $data['UserPvCount']['id'],
                "pv_counter" => $data['UserPvCount']['pv_counter'] + 1,
                "date" => date("Y-m-d"),
                "user_id" => $user_id
            );
        } else {
            $dataSave = array(
                "pv_counter" => 1,
                "date" => date("Y-m-d"),
                "user_id" => $user_id
            );
        }
        if ($this->save($dataSave,false))
            $this->commit();
        else
            $this->rollback();
    }

    public function getChartDataCount ($params) {
        return $this->find('list', array(
            'conditions' => array(
                'UserPvCount.date >=' => $params["start_datetime"],
                'UserPvCount.date <=' => $params["end_datetime"],
                'UserPvCount.user_id' => $params["user_id"]
            ),
            'fields' => array('UserPvCount.date', 'UserPvCount.pv_counter'),
            'order' => array('UserPvCount.date' => 'ASC'),
            "recursive" => -1
        ));
    }
}

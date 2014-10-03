<?php

App::uses('AppModel', 'Model');

/**
 * UserCount Model
 *
 * @property User $User
 * @property Target $Target
 */
class UserCount extends AppModel {

    public $useTable = 'user_count';
    private $__increaseNum = 1;
    private $__decreaseNum = -1;

    public function increaseCounter($params) {
	$data = $this->findByUserIdAndCountGroup($params["user_id"],$params["count_group"]);
        if(!$data) {
            $count = $params["counter"] = $this->__increaseNum;
            $userCountId = $this->_insert($params);
        } else {            
            $point = (isset($params["add_point"])) ? $params["add_point"] : $this->__increaseNum;
            $count = $data['UserCount']['counter'] = $data['UserCount']['counter'] + $point;
            $userCountId = $this->_updateCounter($data['UserCount']);
        }
 
        return array($userCountId, $count);
    }

    public function decreaseCounter($params) {
	$data = $this->findByUserIdAndCountGroup($params["user_id"],$params["count_group"]);
        if(!$data) {
            $count = $params["counter"] = $this->__decreaseNum;
            $userCountId = $this->_insert($params);
        } else {
            $point = (isset($params["add_point"])) ? $params["add_point"] : $this->__decreaseNum;            
            $count = $data['UserCount']['counter'] = $data['UserCount']['counter'] + $point;
            $userCountId = $this->_updateCounter($data['UserCount']);
        }
 
        return array($userCountId, $count);
    }

    protected function _insert($params) {
        $this->create();
        $this->save($params);
        return $this->getLastInsertID();
    }

    protected function _updateCounter($params) {
        $this->id = $params["id"];
        $this->save(array(
            "counter" => $params["counter"] 
        ));
        return $params["id"];
    }

    public function getCountAsNumOfType($params) {
	return $this->find("list", array(
	    "fields" => array("count_group","counter"),
	    "conditions" => array("user_id" => $params["user_id"])
	));
    }

    public function getCountAsNumOfTypeByUserIds($params) {
        if (!isset($params['user_ids']) || !$params['user_ids']) {
            return array();
        }

        return $this->find('list', array(
            'fields' => array('count_group', 'counter', 'user_id'),
            'conditions' => array('user_id' => $params['user_ids'])
        ));
    }

    public function getTotalRanking($params) {
	$sql = "select * from (select user_id,counter,rank() over(order by counter desc) as ranking from user_count where count_group = :count_group) rank where user_id = :id";
	$rankingData = $this->query($sql,$params);
        $ranking = 0;
        if(isset($rankingData[0])){
            $ranking = $rankingData[0][0]['ranking'];
        }
	return $ranking;
    }

    public function increment($params) {
        $this->updateAll(
            array('counter' => 'counter + 1'),
            array()
        );
    }
}

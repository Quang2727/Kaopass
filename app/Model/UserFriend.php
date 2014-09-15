<?php

/* * ******************************************************************
 * User                                                             *
 * (c) 2013-2014 Hiroshi Chiyokawa, You Kobayashi                   *
 * ***************************************************************** */

class UserFriend extends AppModel {

    /**
     * find user friends
     *
     */
    public function findByUserId($user_id = null) {
        if (!$user_id) {
            return false;
        }
        $params = array(
            'conditions' => array(
                "OR" => array(
                    array(
                        'UserFriend.user_id_a' => $user_id,
                    ),
                    array(
                        'UserFriend.user_id_b' => $user_id
                    ))
            ),
        );
        $data = $this->find('all', $params);
        $result[] = -1;
        foreach ($data as $val) {
            if ($val["UserFriend"]["user_id_a"] == $user_id)
                $result[] = $val["UserFriend"]["user_id_b"];
            else
                $result[] = $val["UserFriend"]["user_id_a"];
        }
        return $result;
    }

    /**
     * 
     */
    public function saveSocialFriends($user_id, $friends) {
        if (!$user_id || !$friends) {
            return false;
        }
        $data = array();

        foreach ($friends as $value) {
            if (!$this->checkExistFriend($user_id, $value)) {
                $data[] = array('UserFriend' => array(
                        'user_id_a' => $user_id,
                        'user_id_b' => $value,
                ));
            }
        }
        if (count($data) > 0) {
            return $this->saveAll($data);
        }
        return true;
    }

    /**
     * check exist friends
     *
     */
    private function checkExistFriend($user_id = null, $friend_id = null) {
        if (!$user_id || !$friend_id) {
            return false;
        }
        $params = array(
            'conditions' => array(
                "OR" => array(
                    array(
                        'user_id_a' => $user_id,
                        'user_id_b' => $friend_id,
                    ),
                    array(
                        'user_id_a' => $friend_id,
                        'user_id_b' => $user_id
                    ))
            ),
        );
        $relation = $this->find('first', $params);
        if (is_array($relation) && count($relation) == 1) {
            return true;
        }
    }

    /**
     * find user friend 
     *
     */
    function getListFriend($user_id) {
        $params = array(
            'conditions' => array(
                "OR" => array(
                    array(
                        'user_id_a' => $user_id,
                    ),
                    array(
                        'user_id_b' => $user_id
                    ))
            ),
        );
        $relation = $this->find('all', $params);
        $result = array();
        foreach ($relation as $val) {
            if ($val["UserFriend"]["user_id_a"] == $user_id) {
                $result[$val["UserFriend"]["user_id_b"]] = $val["UserFriend"]["user_id_b"];
            } else {
                $result[$val["UserFriend"]["user_id_a"]] = $val["UserFriend"]["user_id_a"];
            }
        }
        return $result;
    }

}

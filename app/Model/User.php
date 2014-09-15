<?php

class User extends AppModel {

    /**
     * get access token user
     * 
     */
    public function getUserIdByAPIToken($api_token = null) {
        if (!$api_token)
            return 0;
        $ret = $this->findByApiToken($api_token);
        if (is_array($ret) && count($ret) == 1) {
            return $ret['User']['id'];
        }
        return 0;
    }

    /**
     * login user 
     * 
     */
    public function login($user_name = null, $facebook_id = null, $twitter_id = null, $facebook_token = null, $avatar = null, $email = null) {
        $user = array(
            'name' => $user_name,
            'facebook_token' => $facebook_token,
            'avatar' => $avatar,
            'email' => $email,
            'api_token' => sha1(uniqid('', true) . mt_rand())
        );
        if (!empty($facebook_id))
            $user["facebook_id"] = $facebook_id;
        else
            $user["twitter_id"] = $twitter_id;

        if ($new_user = $this->save($user)) {
            return $new_user;
        }

        return 0;
    }

    /**
     * login facebook
     *
     * @param string facebook ID
     * @param string facebook API token
     */
    public function registerByFacebook($facebook_id, $facebook_token) {
        if (!$facebook_id) {
            return false;
        }

        $ret = $this->save(array(
            'User' => array(
                'facebook_id' => $facebook_id,
                'facebook_token' => $facebook_token,
            )
        ));
        if ($ret) {
            return $ret['User']['id'];
        }

        return 0;
    }

    /**
     * get information user from facebook_id
     *
     */
    public function getFacebookIdByUserId($user_id) {
        if (!$user_id) {
            return false;
        }

        $ret = $this->find('first', array(
            'conditions' => array(
                'id' => $user_id,
            ),
        ));
        if ($ret) {
            return $ret['User']['id'];
        }

        return 0;
    }

    /**
     * get information user
     *
     */
    function getInfoUser($user_id) {
        if (!$user_id)
            return false;
        $ret = $this->find('first', array(
            'conditions' => array(
                'id' => $user_id,
            ),
        ));
        if (!$ret)
            return false;
        if (empty($ret["User"]["avatar"])) {
            $ret["User"]["avatar"] = Router::url('/', true) . 'app/systems/avatar/default.png';
        } else {
            if (!filter_var($ret["User"]["avatar"], FILTER_VALIDATE_URL)) {
                $ret["User"]["avatar"] = Router::url('/', true) . $ret["User"]["avatar"];
            } else {
                $ret["User"]["avatar"] = $ret["User"]["avatar"];
            }
        }
        if (empty($ret["User"]["background"])) {
            $ret["User"]["background"] = Router::url('/', true) . 'app/systems/background/default.png';
        } else {
            if (!filter_var($ret["User"]["background"], FILTER_VALIDATE_URL)) {
                $ret["User"]["background"] = Router::url('/', true) . $ret["User"]["background"];
            } else {
                $ret["User"]["background"] = $ret["User"]["background"];
            }
        }
        return $ret["User"];
    }

    /**
     * get information user from list user_id
     *
     */
    public function findAllByUserIds($user_ids) {
        if (!is_array($user_ids))
            return false;

        $users = $this->find('all', array(
            'fields' => array(
                'User.id',
                'User.name',
                'User.facebook_id',
                'User.email',
                'User.avatar',
                'User.twitter_id',
            ),
            'conditions' => array(
                'User.id' => $user_ids,
            ),
        ));
        if (!$users)
            return false;

        return $users;
    }

    /**
     * get information user from list facebook_id
     *
     */
    public function findAllByFacebookIds($facebook_ids) {
        if (!is_array($facebook_ids))
            return false;

        $users = $this->find('all', array(
            'fields' => array(
                'User.id',
            ),
            'conditions' => array(
                'User.facebook_id' => $facebook_ids,
            ),
        ));
        if (!$users)
            return false;

        return $users;
    }

}

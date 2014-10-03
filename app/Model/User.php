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

    public function push_notification($list_id = NULL, $message = NULL, $listMessage = array()) {
        $list_id[] = -1;
        $list_id[] = -2;
        $data = $this->find("all", array(
            "conditions" => array("User.id" => $list_id)
        ));
        foreach ($data as $val) {
            if (!empty($val["User"]["device_token"])) {
                if (!empty($listMessage) && !empty($listMessage[$val["User"]["id"]])) {
                    $this->push_detail($listMessage[$val["User"]["id"]], $val["User"]["device_token"]);
                } else {
                    $this->push_detail($message, $val["User"]["device_token"]);
                }
            }
        }
    }

    public function push_detail($message = NULL, $device_token = NULL) {

        $passphrase = 'hoang';
        $ctx = stream_context_create();
        $path = WWW_ROOT . "ios" . DS . "ck.pem";
        stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $options['loc-key'] = "open";
        $options['data'] = "";
        $options['badge'] = "1";

//        $options['deviceToken'] = "c06ef09e75a6d36773ecc6091e2eeddb268d6857e4e43621d9a64ecb2ad0765d";
//        $options['message'] = "demo";

        $options['message'] = $message;
        $options['deviceToken'] = $device_token;
        $fp = @stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        $body['aps'] = array(
            'alert' => array(
                'body' => $options['message'],
                'action-loc-key' => $options['loc-key'] // 
            ),
            'data' => $options['data'], //
            'badge' => $options['badge'],
            'sound' => 'oven.caf',
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $options['deviceToken']) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
    }

    /**
     * login user 
     * 
     */
    public function login($user_name = null, $facebook_id = null, $twitter_id = null, $facebook_token = null, $avatar = null, $email = null, $device_token = null) {
        $user = array(
            'name' => $user_name,
            'facebook_token' => $facebook_token,
            'avatar' => $avatar,
            'email' => $email,
            'device_token' => $device_token,
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

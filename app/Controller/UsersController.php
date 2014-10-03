<?php

/* * ******************************************************************
 * - UsersController                                                *
 * (c) 2013-2014 Hiroshi Chiyokawa, You Kobayashi                   *
 * ***************************************************************** */

class UsersController extends AppController {

    public $uses = array('User', 'UserFriend', 'UserShare');

    const FIELD_FB = 'id';

    /**
     * update email user
     * 
     */
    function updateEmail() {
        if (empty($this->request->data['email'])) {
            return $this->responseNg('Invalid parameters.');
        }
        $email = $this->request->data['email'];
        $ret = $this->User->find('first', array(
            'conditions' => array(
                'id' => $this->user_id,
            ),
        ));
        $ret["User"]["email"] = $email;
        $this->User->save($ret);
        return $this->responseOk();
    }

    /**
     * create user
     * 
     */
    public function create() {
        $user_name = @$this->request->data['user_name'];
        $facebook_id = @$this->request->data['facebook_id'];
        $facebook_token = @$this->request->data['facebook_token'];
        $twitter_id = @$this->request->data['twitter_id'];
        $avatar = @$this->request->data['avatar'];
        $email = @$this->request->data['email'];
        $device_token = @$this->request->data['deviceToken'];
        if (!$user_name) {
            return $this->responseNg('Invalid parameters.');
        }
        $userLogin = null;
        if (!empty($facebook_id))
            $ret = $this->User->findByFacebookId($facebook_id);
        else
            $ret = $this->User->findByTwitterId($twitter_id);

        if (is_array($ret) && count($ret) == 1) {
            $userLogin = $ret['User'];
            $api_token = $ret['User']['api_token'];
            $ret['User']['device_token'] = $device_token;
            $this->log($ret['User'], "notification");
            $this->User->save($ret['User']);
        } else {
            $user = $this->User->login($user_name, $facebook_id, $twitter_id, $facebook_token, $avatar, $email, $device_token);
            $this->user_id = $user['User']['id'];
            $api_token = $user['User']['api_token'];
            $userLogin = $user['User'];
            $callSocial = 0;
            if (!empty($facebook_id)) {
                $listFriend = $this->getFriendFB($facebook_id, $facebook_token);
                $callSocial = FACEBOOK;
            } else {
                $listFriend = $this->getFriendTW($twitter_id);
                $callSocial = TWITTER;
            }
            $this->registerFriend($listFriend, $callSocial);
        }
        if (empty($userLogin["avatar"])) {
            $userLogin["avatar"] = Router::url('/', true) . 'app/systems/avatar/default.png';
        } else {
            if (!filter_var($userLogin["avatar"], FILTER_VALIDATE_URL)) {
                $userLogin["avatar"] = Router::url('/', true) . $userLogin["avatar"];
            } else {
                $userLogin["avatar"] = $userLogin["avatar"];
            }
        }
        if ($api_token) {
            return $this->responseOk(
                            $userLogin
            );
        } else {
            return $this->responseNg('Cannot create or find user.');
        }
    }

    public function logout() {
        $data = $this->User->findById($this->user_id);
        if (!empty($data)) {
            $data["User"]["device_token"] = "";
            $this->User->save($data);
        }
        return $this->responseOk();
    }

    /**
     * find friend and register
     * 
     */
    public function registerFriend($friend_fb_ids = null, $callSocial = null) {
        $listEmail = @$this->request->data['listEmail'];
        if (!empty($friend_fb_ids)) {
            if ($callSocial == FACEBOOK)
                $friends = $this->User->find("all", array(
                    "conditions" => array(
                        "User.facebook_id" => $friend_fb_ids
                    )
                ));
            else
                $friends = $this->User->find("all", array(
                    "conditions" => array(
                        "User.twitter_id" => $friend_fb_ids
                    )
                ));

            if (!empty($listEmail)) {
                $explode = explode(",", $listEmail);
                $emailFriends = $this->User->find("all", array(
                    "conditions" => array(
                        "User.email" => $explode
                    )
                ));
                if (!empty($emailFriends)) {
                    $friends = array_merge($friends, $emailFriends);
                }
            }
            $client_ap = $this->getIPadress();

            $resultFriend = $this->UserShare->getFriendFromIP($client_ap);
            if (!empty($friends)) {
                foreach ($friends as $val)
                    $resultFriend[$val['User']['id']] = $val['User']['id'];
            }
            if (!empty($resultFriend))
                $this->UserFriend->saveSocialFriends($this->user_id, $resultFriend);
        }
    }

    function FriendFB() {
        $facebook_id = @$this->request->data['facebook_id'];
        $facebook_token = @$this->request->data['facebook_token'];
        $listFriend = $this->getFriendFB($facebook_id, $facebook_token);
        $friends = $this->User->find("all", array(
            "conditions" => array(
                "User.facebook_id" => $listFriend
            )
        ));
        if (!empty($friends)) {
            foreach ($friends as $val)
                $resultFriend[$val['User']['id']] = $val['User']['id'];
            $this->UserFriend->saveSocialFriends($this->user_id, $resultFriend);
        }
        return $this->responseOk();
    }

    function FriendTW() {
        $twitter_id = @$this->request->data['twitter_id'];
        $listFriend = $this->getFriendTW($twitter_id);
        $friends = $this->User->find("all", array(
            "conditions" => array(
                "User.facebook_id" => $listFriend
            )
        ));
        if (!empty($friends)) {
            foreach ($friends as $val)
                $resultFriend[$val['User']['id']] = $val['User']['id'];
            $this->UserFriend->saveSocialFriends($this->user_id, $resultFriend);
        }
        return $this->responseOk();
    }

    function FriendContact() {
        $listEmail = @$this->request->data['listEmail'];
        $explode = explode(",", $listEmail);
        $friends = $this->User->find("all", array(
            "conditions" => array(
                "User.email" => $explode
            )
        ));
        if (!empty($friends)) {
            foreach ($friends as $val)
                $resultFriend[$val['User']['id']] = $val['User']['id'];
            $this->UserFriend->saveSocialFriends($this->user_id, $resultFriend);
        }
        return $this->responseOk();
    }

    /**
     * get friend from facebook
     * 
     */
    function getFriendFB($id = null, $access_token = null) {
        $fields = self::FIELD_FB;
        $result = array();
        $graph_url = "https://graph.facebook.com/{$id}/friends?fields={$fields}&access_token={$access_token}";
        $profile = (array) @json_decode(file_get_contents($graph_url));
        // $this->log($profile, "create");
        if (!empty($profile["data"])) {
            $profile["data"] = (array) $profile["data"];
            foreach ($profile["data"] as $val) {
                $val = (array) $val;
                $result[] = $val["id"];
            }
        }

        return $result;
    }

    /**
     * get friend from twitter
     * 
     */
    function getFriendTW($key = null) {
        App::import('Vendor', 'twitter', array('file' => "twitter" . DS . 'twitteroauth' . DS . 'twitteroauth.php'));
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
        $listFriend = $connection->get('friends/ids', array('user_id' => $key));
        $listFriend = (array) $listFriend;
        $result = array();
        if (!empty($listFriend["ids"])) {
            foreach ($listFriend["ids"] as $val) {

                $result[] = $val;
            }
        }
        return $result;
    }

    /**
     * find friend of user
     * 
     */
    public function findFriends() {
        $friend_ids = $this->UserFriend->findByUserId($this->user_id);
        if (!is_array($friend_ids)) {
            return $this->responseNg('Cannot find friends.');
        }
        $friends = $this->User->findAllByUserIds($friend_ids);

        $result = array();
        foreach ($friends as $val) {
            if (!filter_var($val["User"]["avatar"], FILTER_VALIDATE_URL)) {
                $val["User"]["avatar"] = Router::url('/', true) . $val["User"]["avatar"];
            } else {
                $val["User"]["avatar"] = $val["User"]["avatar"];
            }
            $result[] = $val;
        }
        if (!is_array($result)) {
            return $this->responseNg('Cannot find friends.');
        }
        return $this->responseOk($result);
    }

}

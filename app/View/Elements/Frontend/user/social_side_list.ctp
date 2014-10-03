<?php
if (empty($users)) {
//    echo '<p>ユーザーがありません。</p>';
    echo '<p class="txtNotAcquaintance">Chưa thiết lập liên kết với mạng xã hội. Hãy thiết lập <a href="/users/setting/social/">liên kết với mạng xã hội</a></p>';
} else {
    $num = count($users);
    foreach ($users as $i => $userData) {
        if (empty($userData))
            return false;

        if (empty($myFollowList)) {
            $myFollowList = array();
        }

        if (empty($user_description)) {
            $user_description = '&nbsp;';

            if (isset($userData['UserInfo']['last_login'])) {
                $user_description = 'Lần đăng nhập trước: ' . $this->String->displayPostTime($userData['UserInfo']['last_login']);
            }
        }

        if (isset($userData['User'])) {
            $tmp = $userData['User'];
            unset($userData['User']);
            $userData = array_merge($tmp, $userData);
            unset($tmp);
        }
        $medal_list = array(
          'question_badge' => isset($userData["UserCount"]["6"])?$userData["UserCount"]["6"]:0,
          'reply_badge' => isset($userData["UserCount"]["7"])?$userData["UserCount"]["7"]:0,
          'other_action_badge' => isset($userData["UserCount"]["8"])?$userData["UserCount"]["8"]:0,
        );
$serviceUserData = array();
if(!is_null($userData["SnsInfo"]["user_id"])) {
    $serviceUserData = $service_users[$userData["SnsInfo"]["user_id"]];
}
        $username = $userData["SnsUser"]["display_name"];
        if (!isset($serviceUserData['User']['id'])) {
            $img_src = $userData["SnsUser"]["photo_url"];
            $target = 'target="_blank"';
        } else {
            $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$serviceUserData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];
            $target = '';
        }
        $profile_url = $userData["SnsUser"]["profile_url"];
        $url = '';
        if (isset($serviceUserData['User']['id'])) {
            $url = "/users/".urlencode($serviceUserData['User']['display_name']); 
            $image_url = $url;
        } else {
            $image_url = $profile_url; 
        }
        echo '<li><dl class="boxStat"><dt class="boxStatThumb"><a href="'.$image_url.'" '.$target.'>';
        echo $this->Html->image($img_src, array('alt' => $username, 'class' => 'iconUserThumb_38'));
        echo '</a></dt><dd class="boxUser">';
        echo '<p class="txtTwitterId"><a href="'.$profile_url.'" target="_blank">'.$username.'</a></p>';
        if(isset($serviceUserData['User']['display_name'])) {
        echo '<p class="txtUserName"><a href="'.$url.'">'.mb_strimwidth($serviceUserData['User']['display_name'],0, 22, '...', 'UTF-8').'</a></p>';
        }
        if (isset($serviceUserData['User']['id'])) {

            echo '<p class="boxAddFollow">';
            $is_followed = in_array($serviceUserData['User']['id'], $follows) ? true : false;

            echo $this->Form->button(
                null, 
                array(
                    'class' => 'btnAddFollow enableToggle follow-' . $serviceUserData['User']['id'] . ($is_followed ? ' btnFollowed active btn-success' : '' ). (!isset($User) ? ' btnModalLogin' : ''),
                    'title' => 'Theo dõi',
                    'data-target-user-id' => $serviceUserData['User']['id'],
                    'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
                    'onclick' => (isset($serviceUserData)) ? "follow_user(" . $serviceUserData['User']['id'] . ")" : "",
                    'data' => array(
                        'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                        'follow' => $serviceUserData['User']['id']
                    ),
                    'style' => $is_followed ? "background-position: 0px -22px;" : ""
                )
            );

            echo '</p>';
        }
        echo '</dd>';
        echo '</dl></li>';
    }
}
?>

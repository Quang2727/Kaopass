<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['SnsFriendUser'])) ? $this->request->params['paging']['SnsFriendUser'] : $this->request->params['paging'];
}
$start_index = ($paging['page'] - 1) * $paging['limit'] + 1;
$end_index = $start_index + $paging['current'] - 1;
?>

<div class="well user-holder">
  <div class="data-user">
<?php // echo $this->Element('Frontend/user/user', array("users" => $users, "count" => $count)); ?>
  </div>
</div>
<!------------▼boxContentWrap1 start ------------>
<?php

if (empty($users)) {
  echo '<div class="boxContentWrap" style="margin-top:44px">';
} else {
  echo '<div class="boxContentWrap" style="margin-top:22px">';
}

?>
<!-- 20131129修正　クラス変更　ここから -->
  <div class="boxFilterWrapHead clearfix" style="float:left;margin-top:-22px;">
  <!---- ページング start ---->
<?php
  if($paging['count'] > 0) {
    echo '<div class="boxPager clearfix"><p class="page_counter">';
    echo $start_index. '-'. $end_index . " trong " . $paging['count'].' kết quả';
    echo '</p></div>';
  }
?>
  <!---- ページング end ---->
  </div>
<!-- 20131129修正　クラス変更　ここまで -->

<?php
if (empty($users)) {
//    echo '<p>ユーザーがありません。</p>';
    echo '<p class="txt0number">Không có kết quả trả về <br>Hãy thay đổi từ khóa và tìm lại.</p>';
} else {
    $index = 0;
    $rank = 30 * ($paging['page'] - 1) + 1;
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
        if (isset($serviceUserData['User']['id'])) {
            $url = "/users/".urlencode($serviceUserData['User']['display_name']); 
            $image_url = $url;
        } else {
            $image_url = $profile_url; 
        }
        if($index == 0) {
          echo '<div class="boxFilterWrap" id="userList">';
          echo '<section class="boxUser clearfix">';
          echo '<ul class="boxUserStatList clearfix">';
        }
        echo '<li><div class="boxStat"><div class="boxStatThumb boxStatThumb--social clearfix">';
        $rank += 1;
        echo '<div class="floatL"><a href="'.$image_url.'" '.$target.'>';
        echo $this->Html->image($img_src, array('alt' => $username, 'class' => 'avatar lazyload icnUserThumb'));
        echo '</div>';
        echo '<div class="floatL">';
        echo '<p class="txtTwitterId"><a href="'.$profile_url.'" target="_blank">'.h($username).'</a></p>';
        if (isset($serviceUserData['User']['id'])) {
            echo '<p class="txtUserName txtUserName--social"><a href="'.$url.'">'.h($serviceUserData['User']['display_name']).'</a></p>';
        }
        if (isset($serviceUserData['User']['id'])) {
            $is_followed = in_array($serviceUserData['User']['id'], $follows) ? true : false;

            echo $this->Form->button(
                null, 
                array(
                    'class' => 'btnAddFollow btnAddFollow--social enableToggle follow-' . $serviceUserData['User']['id'] . ($is_followed ? ' btnFollowed active btn-success' : '' ). (!isset($User) ? ' btnModalLogin' : ''),
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

        }
        echo '</div>';
        echo '</div></div></li>';
        $index++;
        if($index == $num) {
            echo '</ul></section></div>';
        }
    }
}
?>
<div class="boxPager clearfix">
<?php
  if($paging['count'] > 0) {
    echo '<p class="page_counter">';
    echo $start_index. ' - '. $end_index . " trong " . $paging['count'] . ' kết quả';
    echo '</p>';
  }
?>
        <ul id="userPager">        
    <?php if ($paging['pageCount'] > 1): ?>
    <?php echo $this->element('Frontend/user/pagination', array('model_name' => 'SnsFriendUser')); ?>
    <?php endif; ?>            
        </ul>            
</div>

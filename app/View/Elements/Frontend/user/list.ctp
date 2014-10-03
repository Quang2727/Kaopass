<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['User'])) ? $this->request->params['paging']['User'] : $this->request->params['paging'];
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
  <div class="boxFilterWrapHead clearfix" style="float:left;margin-top:-22px;">
  <!---- ページング start ---->
<?php
  if($paging['count'] > 0) {
    echo '<div class="boxPager clearfix"><p class="page_counter">';
    echo $start_index. ' - '. $end_index . ' trong '.$paging['count'] . ' kết quả';
    echo '</p></div>';
  }
?>
  <!---- ページング end ---->
  </div>

<?php
if (empty($users)) {
//    echo '<p>ユーザーがありません。</p>';
    echo '<p class="txt0number">Không có kết quả phù hợp. <br>Hãy thay đổi từ khóa và tìm lại.</p>';
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

        $point = $userData['User']['total'];
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

        $is_me = (isset($User) && $User['id'] == $userData['id']);
        $is_followed = in_array($userData['id'], $myFollowList) ? true : false;
        $class_name = ($is_me ? 'me' : '');

        $username = $userData['display_name'];
        $user_url = "/users/".urlencode($username);

        if($index == 0) {
          echo '<div class="boxFilterWrap" id="userList">';
          echo '<section class="boxUser clearfix">';
          echo '<ul class="boxUserStatList clearfix">';
        }
        echo '<li><dl class="boxStat"><dt class="boxStatThumb clearfix">';

        echo '<div class="floatL">';
        if(empty($searchValue) && $paging['page'] == 1) {
          if($index == 0 && $filter != 'filter_news') {echo '<img src="/img/users/icnCup_gold.png" alt="Vàng" class="icnCup">';}
          if($index == 1 && $filter != 'filter_news') {echo '<img src="/img/users/icnCup_silver.png" alt="Bạc" class="icnCup">';}
          if($index == 2 && $filter != 'filter_news') {echo '<img src="/img/users/icnCup_copper.png" alt="Đồng" class="icnCup">';}
        }
        $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];
        if ($userData['delete_flag'] === 0) {
            echo '<a href="'.$user_url.'">';
        }
        echo $this->Html->image($img_src, array('alt' => h($userData['display_name']), 'class' => 'avatar lazyload icnUserThumb'));
        if ($userData['delete_flag'] === 0) {
            echo '</a>';
        }
        echo '</div>';

        echo '<div class="floatL">';
        echo '<p class="txtUserName">';
        if(empty($searchValue) && $filter != 'filter_news') {
            echo '<span class="txtOrder">Hạng '.$rank.'</span> ';
        }
        $rank += 1;
        if ($userData['delete_flag'] === 0) {
            echo ' <a href="'.$user_url.'">';
        }
        echo h(mb_strimwidth($userData['display_name'], 0, 22, '...', 'UTF-8'));
        if ($userData['delete_flag'] === 0) {
            echo '</a>';
        }
        echo '</p>';
        if (isset($User['id']) === false || $userData['id'] != $User['id']) {
            $is_followed = in_array($userData['id'], $follows) ? true : false;

            echo $this->Form->button(
                $is_followed ? 'Đang theo dõi' : 'Theo dõi',
                array(
                    'class' => 'js-follow mod-btn mod-btnFollow mod-icn btnAddFollow follow-' . $userData['id'] . ($is_followed ? ' is-followed btnFollowed active btn-success' : '' ). (!isset($User) ? ' btnModalLogin' : ' enableToggle'),
                    'title' => 'Theo dõi',
                    'data-target-user-id' => $userData['id'],
                    'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
                    'onclick' => (isset($User)) ? "follow_user_sidebar(" . $userData['id'] . ")" : "",
                    'data' => array(
                        'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                        'follow' => $userData['id']
                    ),
                    'style' => $is_followed ? "background-position: 0px -22px;" : ""
                )
            );
        }
        echo '</div><div class="clearfix"></div>';

        echo '</dt>';

        echo '<dd class="boxUserStatus">';
        echo '<div class="clearfix">';
        echo '<p class="txtUserPoint floatL">Điểm: '.number_format($point).'</p>';
        echo '<p class="boxUserPoint floatR">';
        echo '<span class="batch"><img alt="Huy hiệu dành cho câu hỏi" src="/img/common/icnBatch_question.png">'.$medal_list['question_badge'].'</span>';
        echo '<span class="batch"><img alt="Huy hiệu dành cho trả lời" src="/img/common/icnBatch_answer.png">'.$medal_list['reply_badge'].'</span>';
        echo '<span class="batch"><img alt="Huy hiệu dành cho những hành động khác" src="/img/common/icnBatch_action.png">'.$medal_list['other_action_badge'].'</span>';
        echo '</p>';
        echo '</div>';

        if (isset($userData['UserTag'])) {
            $userMyTag = array();
            foreach ($userData['UserTag'] as $tagId) {
                $userMyTag[] = $tags[$tagId];
            }

            echo '<p class="boxUserMyTag">';
            echo '<span class="UserMyTag">';
            echo implode('</span>, <span class="UserMyTag">', $userMyTag);
            echo '</span>';
            echo '</p>';
        }

        echo '</dd>';

        echo '</dl></li>';
        $index++;
        if($index == $num) {
            echo '</ul></section>';
        }
    }

    echo '</div>';
}
?>

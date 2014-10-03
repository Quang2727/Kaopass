<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['User'])) ? $this->request->params['paging']['User'] : $this->request->params['paging'];
}
//print_r($paging);die;
$start_index = ($paging['page'] - 1) * $paging['limit'] + 1;
$end_index = $start_index + $paging['current'] - 1;
?>

<div class="well user-holder">
  <div class="data-user">
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
    echo $start_index . " - " . $end_index . " trong " . $paging['count'] . ' kết quả';
    echo '</p></div>';
  }
?>
  <!---- ページング end ---->
  </div>

<?php
if (empty($users)) {
    echo '<p class="txt0number">';
    if ('Follower' === $paging['model']) {
        echo 'Không có ai theo dõi bạn';
    } else {
        echo 'Bạn chưa theo dõi người khác';
    }
    echo '</p>';
} else {
    $index = 0;
    $rank = 30 * ($paging['page'] - 1) + 1;
    $num = count($users);

    echo '<div class="boxFilterWrap" id="userList">';
    echo '<section class="boxUser clearfix">';
    echo '<ul class="boxUserStatList clearfix">';

    foreach ($users as $i => $userData) {
        if (empty($userData))
            return false;

        if (empty($myFollowList)) {
            $myFollowList = array();
        }

        $point = (isset($userData['UserVoteScore']))? number_format($userData['UserVoteScore']['total']) : '';

        $medal_list = array(
          'gold' => isset($userData["UserCount"]["6"])?$userData["UserCount"]["6"]:0,
          'silver' => isset($userData["UserCount"]["7"])?$userData["UserCount"]["7"]:0,
          'bronze' => isset($userData["UserCount"]["8"])?$userData["UserCount"]["8"]:0,
        );

        if (isset($dataListModel)) {
            $target = $userData['User'];
        } else {
            $target = $userData['Following'];
        }
        $is_followed = in_array($target['id'], $myFollowList) ? true : false;
        $user_url = "/users/".urlencode($target['display_name']);

        echo '<li><dl class="boxStat">';

        echo '<dt class="boxStatThumb clearfix">';

        echo '<div class="floatL">';
        $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$target['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$target['photo'];
        if ($target['delete_flag'] === 0) {
            echo '<a href="'.$user_url.'">';
        }
        echo $this->Html->image($img_src, array('alt' => h($target['display_name']), 'class' => 'avatar lazyload icnUserThumb'));
        if ($target['delete_flag'] === 0) {
            echo '</a>';
        }
        echo '</div>';

        echo '<div class="floatL">';
        echo '<p class="txtUserName">';
        if ($target['delete_flag'] === 0) {
            echo '<a href="'.$user_url.'">';
        }
        echo h(mb_strimwidth($target['display_name'], 0, 22, '...', 'UTF-8'));
        if ($target['delete_flag'] === 0) {
            echo '</a>';
        }
        echo '</p>';
        if (isset($User['id']) === false || $target['id'] != $User['id']) {
            $is_followed = in_array($target['id'], $myFollowList) ? true : false;

            echo $this->Form->button(
                $is_followed ? 'Đang theo dõi' : 'Theo dõi',
                array(
                    'class' => 'js-follow mod-btn mod-btnFollow mod-icn btnAddFollow follow-' . $target['id'] . ($is_followed ? ' is-followed btnFollowed active btn-success' : '' ). (!isset($User) ? ' btnModalLogin' : ' enableToggle'),
                    'title' => 'Theo dõi',
                    'data-target-user-id' => $target['id'],
                    'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
                    'onclick' => (isset($User)) ? "follow_user_sidebar(" . $target['id'] . ")" : "",
                    'data' => array(
                        'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                        'follow' => $target['id']
                    ),
                    'style' => $is_followed ? "background-position: 0px -22px;" : ""
                )
            );
        }
        echo '</div>';

        echo '</dt>';

        echo '<dd class="boxUserStatus">';
        echo '<div class="clearfix">';
        echo '<p class="txtUserPoint floatL">score '.$point.'</p>';
        echo '<p class="boxUserPoint floatR">';
        echo '<span class="batch"><img alt="Huy hiệu dành cho câu hỏi" src="/img/common/icnBatch_question.png">'.$medal_list['gold'].'</span>';
        echo '<span class="batch"><img alt="Huy hiệu dành cho trả lời" src="/img/common/icnBatch_answer.png">'.$medal_list['silver'].'</span>';
        echo '<span class="batch"><img alt="Huy hiệu dành cho những hành động khác" src="/img/common/icnBatch_action.png">'.$medal_list['bronze'].'</span>';
        echo '</p>';
        echo '</div>';

        if (isset($userData['UserTag'])) {
            $userMyTag = array();
            foreach ($userData['UserTag'] as $tagId) {
                $userMyTag[] = $tags[$tagId];
            }

            echo '<p class="boxUserMyTag">';
            echo '<span class="UserMyTag">';
            echo implode('</span>,<span class="UserMyTag">', $userMyTag);
            echo '</span>';
            echo '</p>';
        }

        echo '</dd>';
        echo '</dl></li>';
    }
    echo '</ul></section>';
    echo '</div>';
}
?>
<div class="boxPager clearfix">
    <?php
      if($paging['count'] > 0) {
        echo '<p class="page_counter">';
        echo $start_index. ' - '. $end_index. ' trong ' .$paging['count'] . ' kết quả';
        echo '</p>';
      }
    ?>
    <?php if ($paging['pageCount'] > 1): ?>
    <?php echo $this->element('Frontend/user/connections_list_pagination', array('paging' => $paging)); ?>
    <?php endif; ?>
</div>

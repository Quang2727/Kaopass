<?php
/**
 * @author Mai Nhut Tan
 * @since 2013/09/30
 *
 * @var array $userList
 * @var array $myFollowList
 * @var string $title
 */
if (empty($userList))
    return;

if (empty($myFollowList)) {
    $myFollowList = array();
}
if (empty($title)) {
    $title = 'Thành viên tích cực';
}
?>
<!---- ▼近況 start ---->
<section class="boxLatest clearFix">
  <p class="ttlSub ttlSub_bkgBk ttlPickUpUser">Thành viên tích cực</p>
  <ul id="followScroll" class="boxUserStatList">
      <!-- stat_area start -->
<?php
//print_r($myFollowList);die;
foreach ($userList as $user) { ?>
<?php
$userData = $user;
$user_description = 'PHP Lv.1';
$badge = array(/* describe badge object here */);

if (empty($userData))
    return false;

if (empty($myFollowList)) {
    $myFollowList = array();
}

if (empty($user_description)) {
    $user_description = '&nbsp;';

    if (isset($userData['UserInfo']['last_login'])) {
        $user_description = 'アクティブ: ' . $this->String->displayPostTime($userData['UserInfo']['last_login']);
    }
}

if (isset($userData['User'])) {
    $tmp = $userData['User'];
    unset($userData['User']);
    $userData = array_merge($tmp, $userData);
    unset($tmp);
}

$is_me = (isset($User) && $User['id'] == $userData['id']);
$is_followed = in_array($userData['id'], $myFollowList) ? true : false;
$class_name = ($is_me ? 'me' : '');

$user_link = "/users/{$userData['display_name']}";
$follow_link = array(
    'controller' => 'users',
    'action' => 'followUser'
);
$point = isset($userData["UserVoteScore"]["total"])?$userData["UserVoteScore"]["total"]:0;
$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];

$medal_list = array(
    'question_badge' => isset($userData["UserCount"]["6"])?$userData["UserCount"]["6"]:0,
    'reply_badge' => isset($userData["UserCount"]["7"])?$userData["UserCount"]["7"]:0,
    'other_action_badge' => isset($userData["UserCount"]["8"])?$userData["UserCount"]["8"]:0,
);

if (!empty($myFollowList) && in_array($userData['id'], $myFollowList)) {
    $is_followed = true;
} else {
    $is_followed = false;
}

?>
<li>
  <dl class="boxStat">
    <dt class="boxStatThumb">
      <p class="boxRadius_38"><a href="<?php echo $user_link;?>"><?php
        echo $this->Html->image('/img/' . $img_src, array('class' => 'icnUserThumb_38', 'width' => '32', 'height' => '32', 'alt' => h($userData['display_name'])));
      ?></a></p>
    </dt>
    <dd class="boxUser">
      <p class="txtUserName"><a href="<?php echo $user_link;?>"><?php echo $userData['display_name'];?></a></p>
      <p class="boxUserPoint">
        <span class="point">score <?php echo $point;?></span>
      </p>
      <?php /*

      <button class="btnAddFollow follow-6 enableToggle" title="フォローする" data-target-user-id="6" data-target-follow="0" onclick="follow_user_sidebar(6)" data="0 6" type="submit"></button>
      */ ?>
    </dd>
    <dd class="boxFollowBtn">
      <?php
          echo $this->Form->button(
              $is_followed ? 'Đang theo dõi' : 'Theo dõi',
              array(
                  'class' => 'js-follow mod-btn mod-btnFollow mod-icn btnAddFollow follow-' . $userData['id'] . ($is_followed ? ' is-followed active btn-success' : '' ) . (isset($User) ? ' enableToggle' : '' ),
                  'title' => ($is_followed ? 'Đang theo dõi' : 'Được theo dõi' ),
                  'data-target-user-id' => $userData['id'],
                  'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
                  'onclick' => (isset($User)) ? "follow_user_sidebar(" . $userData['id'] . ")" : "",
                  'data' => array(
                      'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                      'follow' => $userData['id']
                  ),
              )
          );
          /*
          <button class="" title="フォローする" data-target-user-id="30" data-target-follow="0" onclick="follow_user(30)" data="0 30" style="" type="submit">
          </button>


          <button class="btnFollowed active btn-success" title="フォローする" data-target-user-id="206" data-target-follow="1" onclick="follow_user(206)" data="1 206" style="background-position: 0px -22px;" type="submit">
          </button>
           */
      ?>
    </dd>
<?php if (!empty($badge)) { ;?>
    <dd class="txtUserActivity">
      <p>Đã nhận được huy hiệu <span><?php echo  $this->String->displayPostTime('');?></span></p>
    </dd>
<?php
    if (empty($User) || $userData['id'] != $User['id']) {
        $style_tag = $is_followed ? 'style="background-position: 0px 0px;"' : 'style="background-position: 0px -20px;"';
        $class_tag = $is_followed ? ' active btn-success' : ''; 
        $flag = $is_followed ? '1' : '0';
    }
    $member_tag = '';
    $member_class = '';
    if(!empty($User)) {
        $member_tag = 'onclick="follow_user_sidebar('.$userData['id'].')"';
        $member_class = 'btnFollow';
    }
    $class_tag = " btnModalLogin ";
    if(!$is_me) {
?>
    <dd class="boxFollow">
      <button class="btnFollow btnModalLogin bkgSprite follow-<?php echo $userData['id'].$class_tag;?>"
              type="image" src="/img/common/btnFollow.png" data="" <?php echo $member_tag;?> onclick=""
              data-target-follow="<?php echo $flag;?>" data-target-user-id="<?php echo $userData['id'];?>" title="Theo dõi <?php echo $userData['display_name'];?>" <?php echo $style_tag;?>>Theo dõi</button>
    </dd>
<?php
    }

?>
<?php } ?>
  </dl>
</li>    
    
<?php
    echo "\n";
}
?>
  </ul>
</section>

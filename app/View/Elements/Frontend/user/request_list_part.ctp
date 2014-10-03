<?php
/**
 * @var $user_id
 * @var $user_name
 * @var $user_avatar_url
 * @var $user_description
 * @var $requested
 */
if (empty($user_id)) {
    $user_id = '';
}
if (empty($user_name)) {
    $user_name = 'No name';
}

if (!file_exists(IMAGES.$user_avatar_url)) {
    $user_avatar_url = 'users/setting/icnUserSample.jpg';
}

if (empty($requested)) {
    $requested = false;
}

if( empty($tag_list)) {
    $tag_list = array();
}
$list = array();
$count = count($tag_list);
$loop = 2;
if ($count < 2) $loop = $count;
for ($i=0;$i<$loop;$i++) {
  $value = $tag_list[$i];
  $list[$value['Tag']['id']] = $value['Tag']['name'];
}
?>
<li>
  <dl class="boxStat">
    <dt class="boxStatThumb">
      <p class="boxRadius_48">
    <?php
    echo $this->Html->image(
            $user_avatar_url, array(
        'alt' => h($user_name),
        'title' => h($user_name),
        'class' => 'icnUserThumb_48',
        'onerror' => 'this.src = "' . Router::url('/img/users/setting/icnUserSample.jpg', true) . '"; return false;',
        'width' => 50,
        'height' => 50,
        'border' => 0
            )
    );
    ?>
    </p>
    </dt>
    <dd class="boxUser">
      <div class="boxUserTop">
        <p class="txtUserName">
        <?php
            echo $this->html->link($user_name, array(
                'controller' => 'users',
                'action' => 'info',
                'username' => $user_name,
                    ), array(
                'class' => '',
                'title' => $this->String->convertURL($user_name)
            ));
          ?>
        </p>
        <p class="boxUserPoint">
          <span class="txtUserPoint"><?php echo isset($medal_sum_list[5]) ? $medal_sum_list[5] : 0; ?>pt</span>
          <span class="boxUserCate"><?php echo implode(',', $list); ?></span>
        </p>
        <p class="boxUserBadges">
            <img alt="Huy hiệu vàng" src="/img/common/icnBatch_gold.png"><?php echo isset($medal_sum_list[6]) ? $medal_sum_list[6] : 0 ?>
            <img alt="Huy hiệu bạc" src="/img/common/icnBatch_silver.png"><?php echo isset($medal_sum_list[7]) ? $medal_sum_list[7] : 0 ?>
            <img alt="Huy hiệu đồng" src="/img/common/icnBatch_copper.png"><?php echo isset($medal_sum_list[8]) ? $medal_sum_list[8] : 0 ?>
        </p>        
      </div>
    </dd>
    <dd class="boxUserRequest">
      <p class="btnRequest">
<?php if(!$requested) {
    echo '<a>';    
}; ?>
    <?php
    echo $this->Html->image(
            '/img/common/btnRequest.png', array(
        'class' => 'reply-request btn btn-mini pull-right ' . ($requested ? 'btn-success active' : ''),
        'data-target-id' => $user_id,
        'data-target-question-id' => $question_id,
        'data-loading-text' => 'Đang xử lý...',
        'title' => 'Yêu cầu thành viên'.h($user_name).' trả lời câu hỏi',
        'data-placement' => 'left',
        'type' => 'button',
            )
    );
    ?>
<?php if(!$requested) {
    echo '</a>';
}; ?>
      </p>
    </dd>
  </dl>
</li>

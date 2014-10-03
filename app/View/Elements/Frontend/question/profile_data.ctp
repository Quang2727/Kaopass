<?php if (empty($dataList)): ?>
    Chưa có thông tin
<?php else: ?>
    <?php
    switch (true) {
        case in_array($dataListModel, array('ClipQuestion', 'ReplyRequest', 'Question', 'Reply')):
            App::import('Vendor', 'Markdown', array('file' => 'MarkdownExtra' . DS . 'markdown.php'));
            $title_only = in_array($dataListModel, array('ReplyRequest'));
            foreach ($dataList as $questionData) {
                echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData, 'userTags' => $userTags, 'title_only' => $title_only, 'user_id' => $this->request->data['id']));
            }
            break;

        case in_array($dataListModel, array('Follow', 'Follower')):
            echo '<ul class="boxUserStatList clearfix">';

            foreach ($dataList as $i => $userData) {
?>

<?php
$count = 4;
$user_description = 'PHP Lv.1';

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

$point = 0;
foreach ($userData['Vote'] as $value) {
    $point += $value['vote_up'];
}

$tags = (count($userData['Tag']) > 2) ? array_splice($userData['Tag'], 0, 2) : $userData['Tag'];
$tags = array_map(function($val){
    return mb_strimwidth($val,0, 10, '...');
}, $tags);

$is_me = (isset($User) && $User['id'] == $userData['id']);
$is_followed = in_array($userData['id'], $myFollowList) ? true : false;
$class_name = ($is_me ? 'me' : '');

if ($count < 4)
    $style = "style = width:31.404%";
else
    $style = "";

$user_link = array(
    'controller' => 'users',
    'action' => 'info',
    'username' => $userData['display_name'],
);
$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];
?>
<li>
<dl class="boxStat">
<dt class="boxStatThumb">
<?php
echo $this->Html->image(
    $img_src, 
    array(
        'alt' => $userData['display_name'],
        'class' => 'icnUserThumb_55'
    )
);
?>
</dt>
<dd class="boxUser">
<p class="txtUserName">
    <?php
    echo $this->Html->link(
        $userData['display_name'], 
        $user_link, 
            array(
                'title' =>  $this->String->convertURL($userData['display_name'])
            )
        );
    ?>
</p>
<p class="boxUserCate"><?php echo join(',', $tags); ?></p>
<p class="boxUserPoint">
<span class="txtUserPoint"><?php echo number_format($point); ?> pt</span>
</p>
</dd>
<dd class="boxFollow">
<?php 
//TODO ボタンの切り替え対応クラスがない＆位置がおかしい
if (!empty($login_user_id) && $login_user_id === $userData['id']) {
// ログインしているユーザのデータが表示される場合は、フォローボタンを表示しない
} else {
echo $this->Form->button(
        $is_followed ? 'Đang theo dõi' : 'Được theo dõi',
        array(
            'class' => 'js-follow mod-btn mod-btnFollow mod-icn btnAddFollow follow-' . $userData['id'] . ($is_followed ? ' is-followed active btn-success' : '' ) . (isset($User) ? ' enableToggle' : '' ),
            'title' => 'Theo dõi',
            'data-target-user-id' => $userData['id'],
            'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
            'onclick' => (isset($login_user_id)) ? "follow_user_sidebar(" . $userData['id'] . ")" : "",
            'data' => array(
                'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                'follow' => $userData['id']
            ),
            'style' => $is_followed ? "background-position: 0px 0px;" : "background-position: 0px -20px;"
        )
    );
}
?>
</dd>
</dl>
</li>
<?php
                }

            echo '</ul>';
            break;
            
        case ($dataListModel === 'Badge'):
            echo '<ul class="boxBadgeList clearfix">';
            foreach ($dataList as $badge) {
?>
<?php
$medal_condition = Configure::read('medal.condition');
$rule_name = $badge['Medal']['rule_name'];
if(isset($medal_condition[$rule_name])) {
    $count = $badge['UserMedal']['counter'] * $medal_condition[$rule_name];
    $comment = preg_replace('/\$count/i', $count, $badge['Medal']['comment']);
} else {
    $comment = $badge['Medal']['comment'];
}
$badge_path = !empty($badge['Medal']['image']) ? preg_replace('/\/imgBadge_/i', '/mini_', $badge['Medal']['image']) : 'badges/icnBadge_medal.jpg';
?>
<li>
<a href="javascript:void(0);" class="tooltip" title="<?php echo h($comment); ?>">
<span class="icnBage">
<?php 
    echo $this->Html->Image(
        $badge_path,
        array(
            'alt' => __("Huy hiệu"),
        )
    ); 
?>
</span>
<span class="txtBageName"><?php echo h($badge['Medal']['name']); ?></span>
</a>
<p class="txtBageNumber">×<?php echo $badge['UserMedal']['counter']; ?></p>
</li>                
                
<?php
            }
            echo '</ul>';
            break;
    }
    $pagingParams = $this->Paginator->params();
    ?>
<?php
/*
<div class="boxPager clearfix">
<p><?php echo $pagingParams['count']; ?>件中<?php echo $pagingParams['limit'] * ($pagingParams['page'] - 1) + 1 ; ?>-<?php echo $pagingParams['limit'] * ($pagingParams['page'] - 1) + $pagingParams['current']; ?>件を表示</p>
<ul id="pager">
<?php
    foreach(range(1, $pagingParams['pageCount']) as $page){
        if($pagingParams['page'] === $page){
            echo '<li>'.$page.'</li>';
        }else{
            echo '<li><a href="#tab">'.$page.'</a></li>';
        }
    }
?>
*/
?>
</ul>
</div>
<?php endif; ?>

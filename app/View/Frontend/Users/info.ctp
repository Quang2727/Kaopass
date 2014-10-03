<?php
$dataListModel = $model;
$this->assign('body_id', 'pageID_mypage');
$this->Html->css(
    array(
        'users/mypage',
        'tooltipster',
    ),
    null,
    array('inline' => false)
);
$this->Html->script(
    array(
        'frontend/users/info',
        'jquery.imgr',
        'gauge',
        'jquery.tooltipster',
        'txtHidden',
    ),
    array('inline' => false)
);
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
), array('inline' => false, 'block' => 'scriptBottom'));
if (!empty($myFollowList))
    $is_followed = true;
else
    $is_followed = false;
?>

<div id="mypageHeadWrap" class="clearfix">
    <div id="mypageHead" class="clearfix">
        <div id="boxUserName" class="boxStat floatL clearfix">
            <div class="boxStatThumb floatL">
                <?php $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['User']['photo']; ?>
                <p class="boxRadius_95"><?php echo $this->Html->image($img_src, array('alt' => __($userData["User"]["display_name"])));?></p>
            </div>
            <div class="boxUser floatL">
                <div class="clearfix">
                    <h1 class="ttlMain floatL"><?php echo h($userData['User']['display_name']); ?></h1>
<?php

echo $this->Form->hidden('movePage', array('value'=>$model));
echo $this->Form->hidden('idUser', array('value'=>$idUser));
echo $this->Form->hidden('model', array('value'=>$dataListModel));


    if (isset($User) && $userData['User']['id'] == $User['id']) {
    echo '<p class="btnEditprofile floatL">';

    echo $this->Html->link(
            'Sửa thông tin',
            array(
                'controller' => 'users',
                'action' => 'profile'
            ),
            array(
                'escape' => false,
                'class' => 'btn',
            )
        );
}else{
    echo '<p class="userFollow floatL">';
    echo $this->Form->button(
        $is_followed ? 'Đang theo dõi' : 'Theo dõi',
        array(
            'class' => 'js-follow mod-btn mod-btnFollow mod-icn btnAddFollow follow-' . $userData['User']['id'] . ($is_followed ? ' is-followed active btn-success' : '' ) . (isset($User) ? ' enableToggle' : '' ),
            'title' => 'Theo dõi',
            'data-target-user-id' => $userData['User']['id'],
            'data-target-follow' => $is_followed ? FLAG_ON : FLAG_OFF,
            'onclick' => (isset($User)) ? "follow_user_sidebar(" . $userData['User']['id'] . ")" : "",
            'data' => array(
                'value' => $is_followed ? FLAG_ON : FLAG_OFF,
                'follow' => $userData['User']['id']
            ),
            'style' => $is_followed ? "background-position: 0px -22px;" : "background-position: 0px 22px;"
        )
    );
    echo '</p>';
}
?>

                </div>
                <ul class="boxUserData">
                    <li><span class="txtWebadd"><?php if (!empty($userData['UserInfo']['blog'])) { echo $this->Html->link($userData['UserInfo']['blog'], $userData['UserInfo']['blog'], array("rel" => "nofollow", "target" => "_blank"));} ?></span></li>
                    <li>Nơi công tác:<span class="txtCompany"><?php echo h($userData['UserInfo']['department']); ?></span></li>
                    <li>Địa chỉ:<span class="txtAdd"><?php if (isset($userData['UserInfo']['prefecture'])) {echo h($userData['UserInfo']['prefecture']['prefecture_name']);} ?></span></li>
                </ul>
            </div>

            <ul class="boxTag clearfix">
<?php
foreach ($userData['UserTag'] as $tag) {
echo '<li class="bkgCate_s">';
if($tag["Tag"]["question_counter"] > 0) {
echo $this->Html->link(
    $tag['Tag']['name'],
    '/tags/' . $tag['Tag']['name'] . '/'
);
} else {
echo $this->Html->link(
    $tag['Tag']['name'],
    'javascript:void(0);',
    array('class' => 'disabled')
);

}
echo '<div class="boxCate" style="display: none;">';
echo '<p class="ttlCate">';
$counter = (!empty($tag['Tag']['question_counter'])) ? $tag['Tag']['question_counter'] : 0;
echo '<span class="txtQuestion">' . $counter . ' câu hỏi</span>';
echo '</p>';
echo '<span class="txtCate">' .  h($tag['Tag']['explain']) . '</span>';
echo '</div>';
echo '</li>';
}
?>
            </ul>
            <div class="boxProfile txtHidden">
                <p class="txtProfile txt"><?php echo h($userData['UserInfo']['self_info']); ?></p>
            </div>
            <button id="js-txtMore" class="btnMore floatR">...Xem thêm</button>
        </div>
        <div class="boxUserScore floatL">
            <div class="boxTotalScore clearfix">
                <p class="ttlUserScore floatL">Xếp hạng chung</p>
                <dl class="boxScoreData">
                    <dt class="txtUserScore">score<span class="txtTotalScore"><?php echo $score['all']['UserVoteScore']['sum_total']; ?></span>
                    </dt><dd>Vị trí <span class="txtRanking"><?php echo $score['all']['UserVoteScore']['ranking']; ?></span></dd>
                    <dd class="txtRanking_past">(Cao nhất đã đạt vị trí <span class="txtPastRanking"><?php echo ($score['all']['UserVoteScore']['highest_ranking'])? $score['all']['UserVoteScore']['highest_ranking']:'--'; ?></span>)</dd>
                </dl>
            </div>
            <div class="boxWeeklyScore clearfix">
                <p class="ttlUserScore floatL">Xếp theo tuần</p>
                <dl class="boxScoreData">
                    <dt class="txtUserScore">score<span class="txtWeeklyScore"><?php echo $score['week']['UserVoteScore']['sum_total']; ?></span>
                    </dt><dd>Vị trí <span class="txtRanking"><?php echo $score['week']['UserVoteScore']['ranking']; ?></span></dd>
                    <dd class="txtRanking_past">(Cao nhất đã đạt vị trí <span class="txtPastRanking"><?php echo ($score['week']['UserVoteScore']['highest_ranking'])? $score['week']['UserVoteScore']['highest_ranking']:'--'; ?></span>)</dd>
                </dl>
            </div>
            <div class="btnAboutScore">
                <button>score là gì?</button>
                <div class="boxAboutScore">
                    <p class="ttlAboutScore">score là gì?</p>
                    <p>Đánh giá hoạt động của thành viên theo giá trị số dự trên cách tính riêng của teratail.<br>
                        Điểm số càng cao, càng thể hiện thành viên đó là người xuất sắc và tích cực hoạt động.<br>
                         "Đặt câu hỏi", "Trả lời câu hỏi", hay câu hỏi của bản thân được người khác "+" và "theo dõi" là những cách để bạn gia tăng score.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mypageContentsWrap">
    <div id="mainContainer">
<!---- ▽セレクトタブ start ---->
<nav>
<ul id="usrInfoTab" class="boxSelectTab_myPage clearfix">
    <li class="btnWant<?php echo (empty($dataListModel) || $dataListModel == 'ClipQuestion')? ' on usrInfoTabClicked':'';?>" data-type="ClipQuestion" data-tabname="btnWant">
        <p><span>Q&amp;A đã theo dõi</span></p>
    </li>
    <li class="btnQuestion<?php echo ($dataListModel == 'Question')? ' on usrInfoTabClicked':'';?>" data-type="Question" data-tabname="btnQuestion">
        <p><span>Câu hỏi</span></p>
    </li>
    <li class="btnAnswer<?php echo ($dataListModel == 'Reply')? ' on usrInfoTabClicked':'';?>" data-type="Reply" data-tabname="btnAnswer">
        <p><span>Trả lời</span></p>
    </li>
</ul>
</nav>
<!---- セレクトタブ end -------->
<?php
//$Question = array();
//$QuestionCount;
$clipQuestion = array();
$clipQuestionCount;
$replyQuestion = array();
$replyQuestionCount;

switch ($dataListModel) {
    case 'Question':
        break;
    case 'Reply':
        $replyQuestion = $Question;
        $replyQuestionCount = $QuestionCount;
        $Question = array();
        $QuestionCount;
        break;
    default:
        $clipQuestion = $Question;
        $clipQuestionCount = $QuestionCount;
        $Question = array();
        $QuestionCount;
        break;
}
?>

<!------------▼boxContentWrap「クリップしたQ&A」 start ------------>
<section id="boxWant" class="boxContentWrap" style="<?php echo (empty($dataListModel) || $dataListModel == 'ClipQuestion') ? 'display: block;' : 'display: none;'; ?>">
<div id="profile-data-ClipQuestion">
<?php
if (empty($clipQuestion)) {
    echo $this->Element('Frontend/questionlist/notfound');
} else {
echo '<ul>';
foreach ($clipQuestion as $questionData) {
    echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData,'userTags' => $myTagList));
}
echo '</ul>';
}

if(count($clipQuestion) >= LIMIT_QUESTION) {
    echo '<div class="feed_reload hide">1</div>';
}

if (!empty($clipQuestion) && ($clipQuestionCount > LIMIT_QUESTION)) {
  print<<<EOF
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
EOF;
}
?>
</div>
</section>
<!------------ boxContentWrap「クリップしたQ&A」 end ------------->

<!------------▼boxContentWrap「質問」 start ------------>
<section id="boxQuestion" class="boxContentWrap" style="<?php echo ($dataListModel == 'Question') ? 'display: block;' : 'display: none;'; ?>">
<div id="profile-data-Question">
<?php
if (empty($Question)) {
    echo $this->Element('Frontend/questionlist/notfound');
} else {
echo '<ul>';
foreach ($Question as $questionData) {
    echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData,'userTags' => $myTagList));
}
echo '</ul>';
}

if(count($Question) >= LIMIT_QUESTION) {
    echo '<div class="feed_reload hide">1</div>';
}

if (!empty($Question) && ($QuestionCount > LIMIT_QUESTION)) {
  print<<<EOF
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
EOF;
}
?>
</div>
</section>
<!------------ boxContentWrap「質問」 end ------------->

<!------------▼boxContentWrap「回答」 start ------------>
<section id="boxAnswer" class="boxContentWrap" style="<?php echo ($dataListModel == 'Reply') ? 'display: block;' : 'display: none;'; ?>">
<div id="profile-data-Reply">
<?php
if (empty($replyQuestion)) {
    echo $this->Element('Frontend/questionlist/notfound');
} else {
echo '<ul>';
foreach ($replyQuestion as $questionData) {
    echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData,'userTags' => $myTagList));
}
echo '</ul>';
}

if(count($replyQuestion) >= LIMIT_QUESTION) {
    echo '<div class="feed_reload hide">1</div>';
}

if (!empty($replyQuestion) && ($replyQuestionCount > LIMIT_QUESTION)) {
  print<<<EOF
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
EOF;
}
?>
</div>
</section>

<?php
if (isset($User)) {
  $login_class="";
  $login_url=Router::url(array('controller' => 'questions', 'action' => 'input'));
} else {
  $login_class="btnModalLogin";
  $login_url="#";
}
?>

<?php echo $this->Element('Frontend/questionlist/bottom', array('login_class' => $login_class, 'login_url' => $login_url, 'isQuestion' => true));?>

</div>
<div id="sideContainer" class="l-sideContent">
            <section class="boxBadge_side clearfix">
                <p class="ttlSub">Huy hiệu<span class="badgeNum floatR"><span class="badgeNum_have"><?php echo count($userMedalList); ?></span>/<span class="badgeNum_total"><?php echo count($medalList); ?></span></span></p>
                <?php foreach($medalList as $value) {
                    $medalId = $value["Medal"]["id"];
                    if(isset($userMedalList[$medalId])) { ?>
                <p class="imgBadgeSmall floatL">
<?php
echo $this->Html->image($value['Medal']['image'],
    array(
        "alt" => "バッジ",
        "width" => "40",
        "height" => "40"
    )
);
?>
                </p>
                <?php }} ?>
            </section>
            <p class="txtMoreBadge floatR"><a href="/users/<?php echo h($userData['User']['display_name']); ?>/badge">...danh sách huy hiệu</a></p>

            <section class="boxConnection">
                <p class="ttlSub">Liên kết</p>
                <p class="ttlFollow">Bạn đang theo dõi<span class="floatR">×<span class="txtFollow_num"><?php echo h($followingListCount); ?></span></span></p>
                <div class="boxFollow clearfix">
<?php
for ($i=0; $i < count($followingList); $i++) {
    $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$followingList[$i]['Following']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$followingList[$i]['Following']['photo'];
        echo '<div class="boxStatThumb floatL"><p class="boxRadius_40">';
    if ($followingList[$i]['Following']['delete_flag'] === 0) {
        echo '<a href="/users/'.h($followingList[$i]['Following']['display_name']).'">';
        echo $this->Html->image($img_src, array('alt' => h($followingList[$i]['Following']['display_name'])));
        echo '</a>';
    } else {
        echo $this->Html->image($img_src, array('alt' => h($followingList[$i]['Following']['display_name'])));
    }
    echo '</p></div>';
}
?>
                </div>
                <p class="ttlFollower">Đang theo dõi bạn<span class="floatR">×<span class="txtFollower_num"><?php echo h($followerListCount); ?></span></span></p>
                <div class="boxFollower clearfix">

<?php
for ($i=0; $i < count($followerList); $i++) {
    $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$followerList[$i]['Follower']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$followerList[$i]['Follower']['photo'];
        echo '<div class="boxStatThumb floatL"><p class="boxRadius_40">';
    if ($followerList[$i]['Follower']['delete_flag'] === 0) {
        echo '<a href="/users/'.h($followerList[$i]['Follower']['display_name']).'">';
        echo $this->Html->image($img_src, array('alt' => h($followerList[$i]['Follower']['display_name'])));
        echo '</a>';
    } else {
        echo $this->Html->image($img_src, array('alt' => h($followerList[$i]['Follower']['display_name'])));
    }
    echo '</p></div>';
}
?>
                </div>
                <p class="txtMoreConnection floatR"><a href="/users/<?php echo $userData['User']['display_name']; ?>/connections">...danh sách tất cả kết nối</a></p>
            </section>
        </div>
</div>
<!------------ boxContentWrap「回答」 end ------------->

<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo h($userData['User']['display_name']).','.$keyword_for_layout;
$this->end();?>

<?php $this->start('breadcrumb');?>
<li><a href="/users/">Danh sách thành viên</a></li>
<li><?php echo h($userData['User']['display_name']);?></li>
<?php $this->end();?>

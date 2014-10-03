<?php
//if(!empty($show_share_modal)) 
//echo $this->Element('Frontend/question/share_modal');

$this->assign('body_id', 'pageID_question');
$this->assign('complete_msg', 'Đã đăng');
$this->Html->css(array('questions/detail',
'questions/input',
), null, array('inline' => false));
$this->Html->script(array(
    'frontend/ask_user',
), array('inline' => false, 'async' => 'async'));
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
), array('inline' => false, 'block' => 'scriptBottom'));
?>

<?php //機能未実装のため、コメントアウト。
//<!------------▼SNS start ------------>
//<section id="boxPostSNS">
//<p class="ttlSub">質問をSNSで共有して、回答率・回答速度をアップさせましょう！</p>
//<form action="" method="post">
//<div class="boxError">
//  <p class="txtError">110文字以内で入力してください</p>
//</div>
//<div class="boxLabelTextarea">
//<label for="SNSbody">SNSへの投稿内容を入力</label>
//<textarea name="body" id="SNSbody"></textarea>
//</div>
//<p class="txtChu">※110文字以内</p>
//<ul class="btnSubmit clearfix"><li>
//  <a class="a_submit" href="#">
//    <img src="/img/questions/icnGist.gif" alt="Git">Gitに投稿する
//  </a>
//</li>
//<li>
//  <a class="a_submit" href="#">
//    <img src="/img/questions/icnFacebook.gif" alt="Facebook">Facebookに投稿する
//  </a>
//</p>
//<li>
//  <a class="a_submit" href="#">
//    <img src="/img/questions/icnTwitter.gif" alt="twitter">twitterに投稿する
//  </a>
//</li></ul>
//</form>
//</section>
//<!-- SNS end -->
?>

<!------------▼回答を依頼する start ------------>
<section id="boxRequest">
<p class="ttlSub">Bạn muốn ai trả lời câu hỏi này?</p>
<h2 class="ttlMain">Yêu cầu trả lời</h2>

<!---- ▽セレクトタブ start ---->
<nav>
<ul id="tab" class="boxSelectTab clearfix">
<li class="btnRequestUser on"><p><span><img src="/img/questions/detail/btnSelectTabRequest.png" alt="Thành viên liên quan"></span></p></li>
<li class="btnRequestFollow"><p><span><img src="/img/questions/detail/btnSelectTabRequest.png" alt="Thành viên bạn theo dõi"></span></p></li>
<?php //<li class="btnRequestSNS"><p><span><img src="/img/questions/detail/btnSelectTabRequest.png" alt="SNS"></span></p></li> ?>
</ul>
</nav>
<!---- セレクトタブ end -------->


<!------------▼boxContentWrap1 start ------------>
<div class="boxContentWrap" id="tab_users">
<ul class="boxRequestContent nav-stacked">
<?php
if (!empty($user_list)) {
    foreach ($user_list as $user_data) {
    echo $this->Element('Frontend/user/request_list_part', array(
        'user_id' => $user_data['User']['id'],
        'user_name' => $user_data['User']['display_name'],
        'user_avatar_url' => USER_AVATAR_DIR.$user_data['User']['photo'],
        'user_lv' => 'phpLV1',
        'requested' => in_array($user_data['User']['id'], $requested_list),
            'medal_sum_list' => $user_data['MedalSumList'],
            'tag_list' => $user_data['TagList'],
    ));
    }
} else {
    echo '<li>Hệ thống không tìm thấy thành viên phù hợp để trả lời câu hỏi này.</li>';
}
?>
</ul>
<?php
if (count($user_list) >= PAGING) {
echo $this->Html->link(
    '<div class="boxShowMore">
        <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
    </div>
',
    '#', array(
    'class' => 'btn btn-block view-more',
    'data-limit' => PAGING,
    'data-current-page' => 1,
    'data-type' => 'User',
    'data-page-count' => '',
    'data-target' => '#tab_users > ul.nav-stacked',
    'data-target-question-id' => $question_id,
    'escape' => false
));
}
?>
</div>
<!------------ boxContentWrap1 end --------------->

<!-------------▼boxContentWrap2 start ------------>
<div class="boxContentWrap disnon" id="tab_followed">
<ul class="boxRequestContent">
<?php
if (!empty($followed_list)) {
    foreach ($followed_list as $user_data) {
    echo $this->Element('Frontend/user/request_list_part', array(
        'user_id' => $user_data['User']['id'],
        'user_name' => $user_data['User']['display_name'],
        'user_avatar_url' => USER_AVATAR_DIR.$user_data['User']['photo'],
        'user_description' => '',
        'user_lv' => 'phpLV1',
        'requested' => in_array($user_data['User']['id'], $requested_list),
        'medal_sum_list' => $user_data['MedalSumList'],
    ));
    }
} else {
    echo '<li>Bạn chưa theo dõi thành viên nào khác</li>';
}

?>
</ul>
<p class="btnMore">
<?php
if (count($followed_list) >= PAGING) {
echo $this->Html->link(
    $this->Html->image("/img/common/btnMore.png", array("alt" => "Hiển thị thêm")),
    '#', array(
    'class' => 'btn btn-block view-more',
    'data-limit' => PAGING,
    'data-current-page' => 1,
    'data-type' => 'User',
    'data-page-count' => '',
    'data-loading-text' => 'Đang xử lý...',
    'data-target' => '#tab_followed > ul.nav-stacked',
    'data-target-question-id' => $question_id,
    'escape' => false
));
}
?>
</p>
</div>
<!---------- boxContentWrap2 end ---------------->

<!---------▼boxContentWrap3 start ------------>
<div class="boxContentWrap disnon">
<ul class="boxRequestContent">
<li>Chức năng này đang được thực hiện</li>
</ul>
</div>
<!------------ boxContentWrap3 end --------------->

</section>
<!------------ 回答を依頼する end ---------------->
<p class="btnToQuestion"><a href="/questions/<?php echo $question_id; ?>/"><img src="/img/questions/btnToQuestion.jpg" alt="Quay về trang chủ"></a></p>

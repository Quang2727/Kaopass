<?php
echo $this->Html->css(
    array(
        'index',
    ),
    null,
    array('inline' => false)
);
?>
<?php
$sortTypes = Configure::read('question.sort');
$isSeo = false;
if(preg_match('/\/p[0-9]+(\/)?$/',parse_url('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])["path"])) { 
     $isSeo = true;
}
$isQuestion = false;
if (isset($this->request->params['paging']['Question']) && $this->request->params['paging']['Question']['count'] > 0) {
    $isQuestion = true;
}
?>
<div id="boxContentWrap">
<div id="mainContainer">
    <div class="clearfix">
        <h1 class="ttlMain"><span class="txtSearchWord_smaill">[<?php echo h($query->display_query); ?>]</span><span class="txtSearchWord_smaill"> và những câu hỏi liên quan</span></h1>
            <?php if($isSeo) {
                $paging = $this->request->params['paging']['Question'];
                echo '<p class="txtSearchResult">Kết quả tìm kiếm:<span>'.(!empty($paging['count']) ? $paging['count'] : 0) .'</span> câu</p>';
            }?>
    </div>
<?php if ($isQuestion) { ?>
<nav>
    <ul id="tab" class="boxSelectTab boxSelectTab_search clearfix">
        <li class="btnNew">
            <p><span>Mới đăng</span></p>
        </li>
        <li class="btnAttention on">
            <p><span>Đáng chú ý</span></p>
        </li>
        <li class="btnUnresolved">
            <p><span>Chưa giải quyết</span></p>
        </li>
        <li class="btnResolved">
            <p><span>Đã giải quyết</span></p>
        </li>
        <li class="btnUnanswered">
            <p><span>Chưa có trả lời</span></p>
        </li>
    </ul>
</nav>
<?php } ?>
<div class="boxContentWrap btnAttention">
<?php if ($isQuestion) { ?>
<?php
if (empty($questions)) {
    echo $this->Element('Frontend/questionlist/notfound');
} else {
    foreach ($questions as $questionData) {
        echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
    }
    if(count($questions) >= 10) {
        echo '<div class="feed_reload hide">1</div>';
    }
}
?>
<?php if($isSeo) {
    echo $this->element('Frontend/pagination', array('model_name' => 'Question'));
} else { ?>

<?php if(isset($this->request->params['paging']['Question']) && $this->request->params['paging']['Question']['count'] > 10) { ?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php } ?>
<?php } ?>
<?php } ?>
</div>
<div class="boxContentWrap btnMytag" style="display: none;">
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
</div>
<div class="boxContentWrap btnNew" style="display: none;">
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
</div>
<div class="boxContentWrap btnUnresolved" style="display: none;">
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
</div>
<div class="boxContentWrap btnResolved" style="display: none;">
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
</div>
<div class="boxContentWrap btnUnanswered" style="display: none;">
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
</div>
    <div class="loading" style="display:none">Đang xử lý...</div>
<?php
if (isset($User)) {
    $login_class="";
    $login_url=Router::url(array('controller' => 'questions', 'action' => 'input'));
} else {
    $login_class="btnModalLogin";
    $login_url="#";
}
?>

<?php echo $this->Element('Frontend/questionlist/bottom', array('isQuestion' => $isQuestion, 'login_class' => $login_class, 'login_url' => $login_url));?>

</div>
<?php echo $this->Element('Frontend/sidebar');?>
</div>

<?php $this->start('body_id'); ?>pageID_questions<?php $this->end();?>
<?php $this->start('description');
if($this->request->params["paging"]["Question"]["page"] > 1) {
    echo 'Trang thứ '.$this->request->params["paging"]["Question"]["page"].' các câu hỏi liên quan đến '.h($query->query).$description_for_layout;
} else {
    echo 'Các câu hỏi liên quan đến '.h($query->query).$description_for_layout;
}
$this->end();?>
<?php $this->start('keywords');
if($this->request->params["paging"]["Question"]["page"] > 1) {
    echo h($query->query).',trang thứ '.$this->request->params["paging"]["Question"]["page"].','.$keyword_for_layout;
} else {
    echo h($query->query).','.$keyword_for_layout;
}
$this->end();?>

<?php $this->start('breadcrumb');?>
<li><?php echo h($query->query);?> và câu hỏi liên quan</li>
<?php $this->end();?>

<?php
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
    'frontend/feed',
), array('inline' => false, 'block' => 'scriptBottom'));

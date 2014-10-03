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
        <h1 class="ttlMain"><span class="txtSearchWord_smaill">[<?php echo h(urldecode($key_tag)); ?>]</span><span class="txtSearchWord_smaill"> và câu hỏi liên quan</span></h1>
            <?php if($isSeo) { ?>
            <?php
                if(isset($this->request->params['paging']['Question'])) {
                    $paging = $this->request->params['paging']['Question'];
                    echo '<p class="txtSearchResult">kết quả tìm kiếm:<span>'.(!empty($paging['count']) ? $paging['count'] : 0) .'</span> câu hỏi</p>';
                } else {
                    echo '<p class="txtSearchResult">kết quả tìm kiếm:<span>0</span> câu hỏi</p>';
                }
            ?>
            <?php } ?>
    </div>
<?php if ($isQuestion) { ?>
<nav>
    <ul id="tab" class="boxSelectTab boxSelectTab_search clearfix">
        <li class="btnNew" data-tabname="btnNew">
            <p><span>Mới đăng</span></p>
        </li>
        <li class="btnAttention on" data-tabname="btnAttention">
            <p><span>Đáng chú ý</span></p>
        </li>
        <li class="btnUnresolved" data-tabname="btnUnresolved">
            <p><span>Chưa giải quyết</span></p>
        </li>
        <li class="btnResolved" data-tabname="btnResolved">
            <p><span>Đã giải quyết</span></p>
        </li>
        <li class="btnUnanswered" data-tabname="btnUnanswered">
            <p><span>Chưa có câu trả lời</span></p>
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
    if(count($questions) >= LIMIT_QUESTION) {
        echo '<div class="feed_reload hide">1</div>';
    }
}
?>
<?php if($isSeo) {
    echo $this->element('Frontend/pagination', array('model_name' => 'Question'));
} else { ?>

<?php if(isset($this->request->params['paging']['Question']) && $this->request->params['paging']['Question']['count'] > LIMIT_QUESTION) { ?>    
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php } ?>
<?php } ?>
<?php } ?>
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

<?php $this->start('body_id'); ?>pageID_tags<?php $this->end();?>
<?php $this->start('description');
if($this->request->params["paging"]["Question"]["page"] > 1) {
//    echo h($key_tag).'のタグに関連する質問の一覧。'.$this->request->params["paging"]["Question"]["page"].'ページ目です。'.$description_for_layout;
    echo $description_for_layout;
} else {
    echo $description_for_layout;
}
$this->end();?>
<?php $this->start('keywords');
if($this->request->params["paging"]["Question"]["page"] > 1) {
    echo h($key_tag).',trang thứ'.$this->request->params["paging"]["Question"]["page"].','.$keyword_for_layout;
} else {
    echo h($key_tag).','.$keyword_for_layout;
}
$this->end();?>
<?php $this->start('breadcrumb');?>
<li><a href="/tags/">Danh sách tag</a></li>
<li><?php echo h(urldecode($breadcrumb_for_layout));?></li>
<?php $this->end();?>

<?php
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
    'frontend/feed',
), array('inline' => false, 'block' => 'scriptBottom'));

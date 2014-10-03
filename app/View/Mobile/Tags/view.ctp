<?php $this->Html->css(array('sp/index'),null,array('inline' => false)); ?>
<?php $this->Html->script(array('sp/common','sp/feed'),array('inline' => false)); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<?php $this->end();?>
<?php
$isQuestion = false;
if (isset($this->request->params['paging']['Question']) && $this->request->params['paging']['Question']['count'] > 0) {
    $isQuestion = true;
}
?>

    <h1 class="ttlMain"><span class="txtSearchWord"><?php echo h(urldecode($key_tag)); ?></span>Danh sách câu hỏi của</h1>
<?php if ($isQuestion) { ?>
    <nav class="navTabs-up">
      <ul id="tab" class="navTabs navTabs-short boxSelectTab">
        <li class="btnAttention on">Đáng chú ý</li>
        <li class="btnNew">Mới đăng</li>
        <li class="btnUnresolved">Chưa giải quyết</li>
        <li class="btnResolved">Đã giải quyết</li>
      </ul>
    </nav>
<?php } ?>
    <div class="boxContentWrap btnAttention">
<?php if ($isQuestion) { 
if (empty($questions)) {
    echo $this->Element('Frontend/questionlist/notfound');
} else {
    echo '<ul>';
    foreach ($questions as $questionData) {
      echo $this->Element('Mobile/question/list_part', array('questionData' => $questionData));
    }
    echo '</ul>';
    if(count($questions) >= LIMIT_QUESTION) {
      echo '<div class="feed_reload hide">1</div>';
    }
}
?>

<?php if(isset($this->request->params['paging']['Question']) && $this->request->params['paging']['Question']['count'] > LIMIT_QUESTION) { ?>    
      <div class="boxShowMore">
        <button class="btnShowMore btn">...Xem thêm </button>
      </div>
<?php } ?>
<?php } ?>
    </div>
    <div class="boxContentWrap btnNew" style="display: none;">
      <div class="boxShowMore loading">
        <button class="btnShowMore btn">...Xem thêm</button>
      </div>
    </div>
    <div class="boxContentWrap btnUnresolved" style="display: none;">
      <div class="boxShowMore loading">
        <button class="btnShowMore btn">...Xem thêm</button>
      </div>
    </div>
    <div class="boxContentWrap btnResolved" style="display: none;">
      <div class="boxShowMore loading">
        <button class="btnShowMore btn">...Xem thêm</button>
      </div>
    </div>
<?php $this->start('body_id'); ?>pageID_tags<?php $this->end();?>
<?php
$this->assign('body_id', 'pageID_mypage');
$this->Html->script(
    array(
        'frontend/users/connections',
        'frontend/users/search',
        'jquery.imgr',
        'gauge',
        'jquery.tooltipster',
    ),
    array('inline' => false)
);
$this->Html->scriptStart(array('inline'=>false));
?>
<?php
$this->Html->scriptEnd();
echo $this->form->hidden('connection_user_id', array('value'=>$userData['User']['id']));
?>
<?php  $this->assign('body_id', 'pageID_connectlist'); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Các liên kết của <?php echo h($userData['User']['display_name']); ?></li>
<?php $this->end();?>
<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['User'])) ? $this->request->params['paging']['User'] : $this->request->params['paging'];
}
?>

<!------------▼ユーザー一覧 start ------------>
  <h1 class="ttlMain">Danh sách liến kết của <span class="txtUserName"><?php echo h($userData['User']['display_name']); ?></span></h1>
  <!---- ▽セレクトタブ start ---->
  <nav>
    <ul id="connectionsTab" class="boxSelectTab clearfix">
      <li class="on" id="tabFollowing" class="usrInfoTabClicked">
        <p><span>Bạn đang theo dõi</span></p>
      </li>
      <li id="tabFollower">
        <p><span>Thành viên theo dõi bạn</span></p>
      </li>
    </ul>
  </nav>
  <!---- セレクトタブ end -------->

<div id="ajax_update">
<?php echo $this->element('Frontend/user/connections_list'); ?>
<?php /*
<div class="boxPager clearfix">
    <p class="page_counter">
    <?php echo $this->element('Frontend/user/count_view', array('model_name' => 'User')); ?>
    </p>
    <?php if ($paging['pageCount'] > 1): ?>
    <?php echo $this->element('Frontend/user/connections_list_pagination', array('model_name' => 'User')); ?>
    <?php endif; ?>
</div>
*/ ?>
</div>
</div>
<div id="ajax_update_follower" style="display:none;">
<div class="well user-holder">
<div class="data-user"></div>
</div>
<div class="boxContentWrap" style="margin-top:44px">
<div class="boxFilterWrapHead clearfix" style="float:left;margin-top:-22px;"></div>
<p class="txt0number">Hiện tại không có ai đang theo dõi bạn</p>
<div class="boxPager clearfix"></div>
</div>
</div>

<?php
$this->Html->css(array(
    'users/users',
    'users/connections',
    'users/user_tooltipster'
), NULL, array('inline' => false));
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
), array('inline' => false, 'block' => 'scriptBottom'));

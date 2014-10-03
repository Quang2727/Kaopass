<?php  $this->assign('body_id', 'pageID_userlist'); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Tìm người quen</li>
<?php $this->end();?>
<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['SnsFriendUser'])) ? $this->request->params['paging']['SnsFriendUser'] : $this->request->params['paging'];
}
?>

<!------------▼ユーザー一覧 start ------------>
<h1 class="ttlMain">Tìm người quen</h1>

  <!---- ▽検索 start ---->
  <div id="boxContentSearch">
<?php
  echo '<form action="/users/list/social" class="navbar-search-realtime" id="keyword-search" method="post" onsubmit="return false;">';
  $keyword = isset($searchValue) ? $searchValue : '';
  $keyword_param = isset($searchValue) ? '/'.$searchValue.'/' : '';
?>
    <div class="boxLabelInput">
      <input type="hidden" id="pageTag" name="page" value="1" class="txtSearch" >
    </div>
  </form>
</div>
<!---- 検索 end -------->


<div id="ajax_update">
<?php echo $this->element('Frontend/user/social_list', array('model_name' => 'SnsFriendUser')); ?>
</div>
</div>


<?php $this->Html->css('/css/users/users.css', NULL, array('inline' => false)); ?>
<?php $this->Html->css(array('/css/users/user_tooltipster.css'), NULL, array('inline' => false)); ?>
<?php $this->Html->script(array('/js/jquery.tooltipster.js'), array('inline' => false)); ?>
<?php $this->Html->script('/js/frontend/users/search.js', array('inline' => false)); ?>

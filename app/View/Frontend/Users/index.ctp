<?php  $this->assign('body_id', 'pageID_userlist'); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Danh sách thành viên</li>
<?php $this->end();?>
<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($this->request->params['paging']['User'])) ? $this->request->params['paging']['User'] : $this->request->params['paging'];
}
?>

<!------------▼ユーザー一覧 start ------------>
<h1 class="ttlMain">Tìm kiếm thành viên</h1>
  <input type="hidden" name="filter" id="filter" value="reply" />
  <input type="hidden" name="sort" id="sort" value="" />
  <!---- ▽セレクトタブ start ---->
  <nav>
    <ul id="userSearchTab" class="boxSelectTab clearfix">
<?php
$filterTypes = Configure::read('user.filter3');
foreach ($filterTypes as $type => $label) {
    $check = ($type == $filter) ? 'on' : '';
    echo '<li class="'.$check.'" id="filter_'. $type .'" >'.
         '<p><span>'.$label.'</span></p></li>'; 
}
?>
    </ul>
  </nav>
  <!---- セレクトタブ end -------->


  <!---- ▽検索 start ---->
  <div id="boxContentSearch">
<?php
  echo '<form action="/ajax_users" class="navbar-search-realtime" id="keyword-search" method="post" onsubmit="return false;">';
  $keyword = isset($searchValue) ? $searchValue : '';
  $keyword_param = isset($searchValue) ? '/'.$searchValue.'/' : '';
?>
    <div class="boxLabelInput">
      <input type="text" id="keywordTag" name="search" size="15" value="<?php echo $keyword; ?>" class="txtSearch" placeholder="Nhập tên thành viên để tìm kiếm">
      <input type="hidden" id="typeTag" name="type" value="filter_total" class="txtSearch">
      <input type="hidden" id="pageTag" name="page" value="1" class="txtSearch">
    </div>
    <p class="btnSearch a_submit mod-icn">Tìm kiếm</p>
  </form>
</div>
<!---- 検索 end -------->
<?php
  echo '<input type="hidden" name="filter" id="filter" value="'. $filter . '" />';
?>

<div id="ajax_update">
<?php echo $this->element('Frontend/user/list'); ?>
</div>
</div>

<div class="boxPager clearfix">
    <p class="page_counter">
    <?php echo $this->element('Frontend/user/count_view', array('model_name' => 'User')); ?>
    </p>
        <ul id="userPager">        
    <?php if ($paging['pageCount'] > 1): ?>
    <?php echo $this->element('Frontend/user/pagination', array('model_name' => 'User')); ?>
    <?php endif; ?>            
        </ul>            
</div>

<?php
$this->Html->css(array(
    'users/users',
    'users/user_tooltipster'
), NULL, array('inline' => false)); ?>
<?php
$this->Html->script(array(
    'jquery.tooltipster.js',
    'frontend/users/search',
    'plugins/bootstrap-tabdrop',
), array('inline' => false, 'block' => 'scriptBottom'));

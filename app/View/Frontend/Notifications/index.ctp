<div class="clearfix">
    <h1 class="ttlMain">Danh sách thông báo</h1>
</div>

<div class="boxContentWrap">
<?php if(!empty($dataAlert)) { ?>
<ul>
<?php foreach($dataAlert as $key => $value) { ?>
    <?php
      $className = ($value["read_flag"] == 0) ? ' bkgNew' : '';
  ?>
<li class="notification_boxItem<?php echo $className; ?>">
<?php echo $value["message"]; ?>
<?php
  $notificationDate = (isset($value['modified']))? date('Y/m/d H:i', strtotime($value['modified'])) : date('Y/m/d H:i', strtotime($value['created']));
?>
<span class="notificationDate"><?php echo $notificationDate;?></span>
</li>

<?php } ?>
</ul>
<?php } else { ?>
<div class="boxItemContent clearfix">
    <p>Hiện tại bạn không có thông báo</p>
</div>
<?php } ?>
</div>

<?php $this->start('body_id'); ?>pageID_notification<?php $this->end();?>

<?php $this->start('breadcrumb');?>
<li>Danh sách thông báo</li>
<?php $this->end();?>

<?php
$this->Html->css(array(
    'style',
    'notifications/style',
), null, array('inline' => false));
?>

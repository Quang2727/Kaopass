<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css('users/setting', null, array('inline' => false));

$this->assign('body_id', 'pageID_mail');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Xác nhận địa chỉ mail</li>
<?php $this->end();?>

<h1 class="ttlMain ico-user">Chức năng chỉ có thể sử dụng sau khi xác nhận mail</h1>
<section class="boxPost unconfirmed-boxPost clearfix">
<?php echo $this->Form->create(false, array('type' => 'post', 'action' => 'setting')); ?>
<section class="boxPostComp">
<p>Xin vui lòng xác nhận mail trước khi sử dụng các chức năng bên dưới<br>
・Thay đổi thông tin cá nhân<br>
・Thay đổi mật khẩu</p>
<p class="unconfirmed-txtCenter">Xin vui lòng nhấn nút bên trên để xác nhận địa chỉ mail.</p>
<?php /*
<p class="unconfirmed-txtCenter">下のボタンを押して、メールアドレスを認証してください。</p>
<div class="btnWrap-center">
<button class="unconfirmed-sendBtn">認証メールを送信</button>
</div>
*/ ?>
</section>
<?php echo $this->Form->end(); ?>
</section>

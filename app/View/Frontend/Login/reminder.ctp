<div id="mainContainer">

<h1 class="ttlMain ttlForget">Thay đổi mật khẩu</h1>

<section class="boxPost">
<p class="txtForget">Hãy nhập mật khẩu mới</p>
<?php echo $this->Form->create('User',array('inputDefaults' => array())); ?>
<?php
    if(isset($errors)) {
        echo '<div class="boxError">';
        foreach($errors as $error) {
            echo '<p class="txtError">'.$error['0'].'</p>';
        }
        echo '</div>';
    }
?>
<dl>
<dt class="dtForget">Mật khẩu mới<span class="txtRequired">* bắt buộc</span></dt>
<dd class="ddForget"><?php echo $this->Form->input('password', array(
          'type' => 'password',
          'maxlength' => 45,
          'label' => false,
          'error' => false,
          'div' => false
        ));
      ?><span class="txtPasswordDetail">6 ~ 20 ký tự</span></dd>
<dt class="dtForget">Xác nhận lại mật khẩu<span class="txtRequired">* bắt buộc</span></dt>
<dd class="ddForget"><?php echo $this->Form->input('repeat_password', array(
          'type' => 'password',
          'maxlength' => 45,
          'label' => false,
          'error' => false,
          'div' => false
        ));
      ?></dd>
</dl>
<button type="submit" class="mod-btn mod-btnSignup mod-icn l-btnSend" value="Gửi">Gửi</button>
<?php echo $this->Form->end(); ?>
</section>
</div>

<?php $this->start('body_id'); ?>pageID_pw<?php $this->end();?>

<?php
echo $this->Html->css(array(
    'users/setting',
));
?>

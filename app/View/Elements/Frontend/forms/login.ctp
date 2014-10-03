<?php
  echo $this->Form->create(null, array(
    'url' => 'https://'.$_SERVER['HTTP_HOST'] . '/login/request',
    'id' => 'login_form'));
?>

<?php if(!empty($errors)) { ?>
<div class="boxError">
<?php foreach($errors as $error) { ?>
        <p class="txtError"><?php echo$error['0']; ?></p>
<?php } ?>
</div>
<?php } ?>

<ul class="l-formLists-login">
  <li>
    <input type="text" value="" id="mail2" class="mod-inputField mod-inputField-max" name="data[User][mail_address]" placeholder="Địa chỉ mail">
  </li>
  <li>
    <input type="password" value="" id="passwd2" class="mod-inputField mod-inputField-max" name="data[User][password]" placeholder="Mật khẩu">
  </li>
</ul>
<p class="persistent"><input type="checkbox" checked="" value="" name="data[use_cookies]" id="UserCookies"><label for="persistent">Luôn ở trạng thái đăng nhập</label></p>
<div>
  <button type="submit" id="save" class="mod-btn mod-btnSignup mod-icn l-btnLogin-center">Đăng nhập</button>
</div>

<?php echo $this->Form->end(); ?>

<p class="bkgNewAccount clearfix">

<?php echo $this->Html->link(
    'Đăng ký tài khoản mới',
    array(
        'controller' => 'login',
        'action' => 'input'
    ),
    array(
        'escape' => false
    )
); ?>
&nbsp;&nbsp;
<?php echo $this->Html->link(
    'Quên mật khẩu',
    array(
        'controller' => 'login',
        'action' => 'forget'
    ),
    array(
        'escape' => false
    )
); ?>
</p>

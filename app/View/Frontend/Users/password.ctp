<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css('users/setting', null, array('inline' => false));

$this->assign('body_id', 'pageID_pw');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
    <?php if($actionChange) {?>
<li>Thay đổi mật khẩu</li>
    <?php } else { ?>
<li>Hoàn thành thay đổi mật khẩu</li>
    <?php } ?>
<?php $this->end();?>

<!-- -----------  ------- Main start ------------------ -- -->
<div id="mainContainer">
    <?php if ($actionChange) { ?>
  <h1 class="ttlMain">Thay đổi mật khẩu</h1>
  <section class="boxPost">
    <?php
    echo $this->Form->create('User', array('inputDefaults' => array(
        'label' => false,
        'div' => false,
        "required" => false,
      ),
    ));
    ?>
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
      <?php if (!$emptyOldPassword): ?>
      <dt>Mật khẩu hiện tại*</dt>
      <dd><?php echo $this->Form->input('passwordOld', array(
          'type' => 'password',
          'maxlength' => 45,
          'label' => false,
          'error' => false,
        ));
      ?></dd>
      <?php endif; ?>

      <dt>Mật khẩu mới*</dt>
      <dd><?php echo $this->Form->input('password', array(
          'type' => 'password',
          'maxlength' => 45,
          'label' => false,
          'error' => false,
        ));
      ?><span>6 ~ 20 ký tự</span></dd>

      <dt>Xác nhận lại mật khẩu*</dt>
      <dd><?php echo $this->Form->input('repeat_password', array(
          'type' => 'password',
          'maxlength' => 45,
          'label' => false,
          'error' => false,
        ));
      ?></dd>
    </dl>
    <?php
      echo $this->Form->input('Thay đổi', array(
        'type' => 'button',
        'div' => false,
        'alt' => '変更する',
        'class' => 'mod-btn mod-btnSignup mod-icn l-btnSignup-center',
      ));
    ?>
    <p class="bkgNewAccount"><a href="/login/forget/input">Bạn quên mật khẩu?</a></p>
  </section>
    <?php  } else { ?>
  <h1 class="ttlMain">Hoàn thành thay đổi mật khẩu</h1>
  <section class="boxPost">
    <p>Mật khẩu đã được thay đổi.<br>Hệ thống đã gửi mail xác nhận thay đổi mật khẩu đến bạn.</p>
    <?php } ?>
                <?php echo $this->Form->end(); ?>
  </section>
</div>
<!-------------------- Main end ---------------------->


<!-------------------- Side start ---------------------->
<div id="sideContainer" class="l-sideContent"> 
    <nav>
        <ul class="boxSettingSelect">
            <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'profile')) ?>" class="sidebar">Thay đổi thông tin</a></li>
            <li class="sidebar_active">Thay đổi mật khẩu</li>
            <li><a href="/users/setting/social" class="sidebar">Thiết lập liên kết SNS</a></li>
        </ul>
    </nav>
</div>

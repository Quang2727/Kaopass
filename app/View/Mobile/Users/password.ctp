<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css(array('sp/login/input','sp/users/setting.css'), null, array('inline' => false));

$this->assign('body_id', 'pageID_pw');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>


<!-- -----------  ------- Main start ------------------ -- -->
<div class="content">    
  <?php if ($actionChange) { ?>
  <div class="boxForm">
    <h2 class="ttlMain">Thiết lập mật khẩu</h2>
    <nav class="navTabs-up">
        <ul class="navTabs navTabs-full">
            <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'profile')) ?>" class="sidebar">Thay đổi thông tin/a></li>
            <li><a href="/users/setting/social" class="sidebar">Thiết lập liên kết SNS</a></li>
        </ul>
    </nav>
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
    <ul class="boxForm__listBlock">
      <?php if (!$emptyOldPassword): ?>
      <li class="boxForm__listBlock__list">
          <p class="ttlInput">Mật khẩu hiện tại*</p>
          <?php echo $this->Form->input('passwordOld', array(
              'type' => 'password',
              'maxlength' => 45,
              'label' => false,
              'error' => false,
              'class' => 'inputSize-xlarge',
            ));
          ?>
      </li>
      <?php endif; ?>

      <li class="boxForm__listBlock__list">
          <p class="ttlInput">Mật khẩu mới*　<span class="ttlInputCap">6~20 ký tự/span></p>
          <?php echo $this->Form->input('password', array(
              'type' => 'password',
              'maxlength' => 45,
              'label' => false,
              'error' => false,
              'class' => 'inputSize-xlarge',
            ));
          ?>
      </li>

      <li class="boxForm__listBlock__list">
          <p class="ttlInput">Xác nhận lại mật khẩu*</p>
          <?php echo $this->Form->input('repeat_password', array(
              'type' => 'password',
              'maxlength' => 45,
              'label' => false,
              'error' => false,
              'class' => 'inputSize-xlarge',
            ));
          ?>
      </li>
    </ul>
    <p class="btnSubmit">
      <!--a class="a_submit" href="#"><img src="/img/users/setting/btnChangePw.jpg" alt="Thay đổi"></a-->
      <?php
        echo $this->Form->submit('Thay đổi', array(
          'div' => false,
          'alt' => 'Thay đổi',
          'class' => 'submitBtn submitBtn-large',
        ));
      ?>
    </p>
    <!-- <p class="bkgNewAccount"><a href="/login/forget/input">Bạn quên mật khẩu</a></p> -->
    <ul class="txtLink-group">
      <li><a href="/login/forget/input" class="icoArrow icoImg-blue">Bạn quên mật khẩu</a></li>
    </ul>
    <?php echo $this->Form->end(); ?>
    </section>
  </div>
  <?php } else { ?>
  <div class="boxForm">
    <h2 class="ttlMain">Hoàn thành việc thay đổi mật khẩu</h2>
    <section class="boxPost">
      <p>Mật khẩu của bạn đã thay đổi.<br>Chúng tôi đã gửi một email xác nhận, bạn vui lòng kiểm tra email.</p>
    </section>
  </div>
  <?php } ?>
</div>
<!-------------------- Main end ---------------------->

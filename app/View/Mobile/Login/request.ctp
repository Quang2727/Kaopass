<?php $this->Html->css(array('sp/login/input', 'sp/login/request'),null,array('inline' => false)); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<?php $this->end();?>


<div class="content">
<?php
  echo $this->Form->create(null, array(
    'url' => 'https://'.$_SERVER['HTTP_HOST'] . '/login/request',
    'id' => 'UserSignupForm',
    'class' => 'form-horizontal margin-none',
  ));
?>
    <div class="boxForm">
      <h2 class="ttlMain ttlMain--request">Đăng nhập bằng tài khoản SNS/Đăng ký mới</h2>
      <div class="boxForm__snsBlockArea">
        <ul class="snsBlock clearfix">
        <?php foreach(array('Facebook' => 'facebook', 'Twitter' => 'twitter', 'Google' => 'google','Github' => 'GitHub', 'Hatena' => 'Hatena') as $brand => $name){?>
          <li class="btnLogin snsBlock__list">
            <?php
            $brand_lowercase = strtolower($brand);
            echo $this->Html->link(
                '<img src="/img/sp/common/login/icnLogin' . $brand.'.png" alt="Đăng nhập bằng'.$brand.'" width="50" height="50">',
                array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                array('class' => '', 'escape' => false)
            );
            ?>
          </li>
        <?php } ?>
        </ul>
        <p>Ngoài ra</p>
      </div>
      <ul class="boxForm__listBlock">
        <li class="boxForm__listBlock__list">
          <input name="data[User][mail_address]" id="mail_address" class="txtInputArea inputSize-xlarge" placeholder="Địa chỉ Email" type="text" required/>
        </li>
        <li class="boxForm__listBlock__list">
          <input name="data[User][password]" id="password" class="txtInputArea inputSize-xlarge" placeholder="Mật khẩu" type="password" required/>
        </li>
      </ul>
<?php
    if (isset($errors) && is_array($errors) && count($errors) > 0) {
      echo '<div class="boxError">';
      foreach ($errors as $key => $err) {
        echo '<p class="txtError">'.$err[0].'</p>';
      }
      echo '</div>';
    }
?>

      <div class="submitBtn-up">
        <input type="submit" id="save" class="submitBtn submitBtn-large" value="Đăng nhập">
      </div>
      <ul class="txtLink-group">
        <li>
          <a href="/login/input" class="icoArrow icoImg-blue">Đăng ký tài khoản mới Tại đây</a>
        </li>
        <li>
          <a href="/login/forget/input" class="icoArrow icoImg-blue">Quên mật khẩu</a>
        </li>
      </ul>
    </div>
<?php echo $this->Form->end(); ?>
</div>

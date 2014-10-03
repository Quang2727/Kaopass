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
  echo $this->Form->create('/login/input', array(
    'class' => 'form-horizontal margin-none',
    'id' => 'UserSignupForm'));

?>
    <div class="boxForm">
      <h2 class="ttlMain">Đăng ký thành viên mới</h2>
      <?php
          echo '<div class="boxError">';
          echo $this->Form->error('TmpUser.display_name', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.mail_address', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.password', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.repeat_password', null,array('wrap' => 'p','class' => 'txtError'));
          echo '</div>';
      ?>

      <ul class="boxForm__listBlock">
        <li class="boxForm__listBlock__list">
          // <p class="ttlInput">Tên đăng nhập　<span class="ttlInputCap">sử dụng các ký tự-_. và dưới 15 ký tự</span></p>
          <?php echo $this->Form->input(
              'TmpUser.display_name',
              array(
                  'type' => 'text',
                  'id' => 'name',
                  'class' => 'txtInputArea inputSize-xlarge',
                  'div' => false,
                  'label' => false,
                  'error' => false,
                  'maxlength' =>"15",
              )
          ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <p class="ttlInput">Địa chỉ Email</p>
          <?php echo $this->Form->input(
              'TmpUser.mail_address',
              array(
                  'type' => 'text',
                  'id' => 'mail_address',
                  'class' => 'txtInputArea inputSize-xlarge',
                  'div' => false,
                  'label' => false,
                  'error' => false,
              )
          ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <p class="ttlInput">Mật khẩu　<span class="ttlInputCap">6~20 ký tự</span></p>
          <?php echo $this->Form->input(
              'TmpUser.password',
              array(
                  'type' => 'password',
                  'id' => 'password',
                  'class' => 'txtInputArea inputSize-xlarge',
                  'div' => false,
                  'label' => false,
                  'error' => false,
              )
              ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <p class="ttlInput">Mật khẩu<span class="ttlInputCap">(Xác nhận lại)</span></p>
          <?php echo $this->Form->input(
                'TmpUser.repeat_password',
                array(
                    'type' => 'password',
                    'id' => 'password_check',
                    'class' => 'txtInputArea inputSize-xlarge',
                    'div' => false,
                    'label' => false,
                    'error' => false,
                )
            ); ?>
        </li>
      </ul>
<?php
    if ($this->form->isFieldError) {
    echo '<div class="boxError">';
    echo $this->Form->error('TmpUser.display_name', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.mail_address', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.password', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.repeat_password', null,array('wrap' => 'p','class' => 'txtError'));
    echo '</div>';
    }
?>

      <?php
        //unset shown data
        if (isset($other_data) && is_array($other_data)) {
          unset($other_data['TmpUser']);
          //hidden data is here
          foreach($other_data as $index => $fields){
            if(is_array($fields) === false) continue;
            foreach($fields as $field => $value){
              if(is_array($value) === false){
                echo $this->Form->hidden("{$index}.{$field}");
                continue;
              }
              if(is_numeric($field) === false) continue;
              foreach($value as $f => $v){
                echo $this->Form->hidden("{$index}.{$field}.{$f}");
              }
            }
          }
        }
      ?>
      <p class="boxForm__txtInput"> 
        <a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/sp/common/10822633_04_75_jp.gif" alt="privacy mark" width="40" height="40"></a>
        <a href="/legal/" target="_blank">Điều khoản sử dụng</a>, và <a href="/privacy/" target="_blank">Xử lý thông tin cá nhân</a>Sau khi đã tìm hiểu và xác nhận các điểm cơ bản về, nếu bạn đồng ý với các điều khoản, vui lòng nhấn nút "Đồng ý gửi". 
      </p>
      <div class="submitBtn-up">
        <input type="submit" id="save" class="submitBtn submitBtn-large" value="Đồng ý đăng ký">
      </div>
    </div>
  <?php echo $this->Form->end(); ?>
</div>

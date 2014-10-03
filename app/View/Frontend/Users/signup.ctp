<?php
$this->assign('body_id',' ');
$this->Html->css(array(
    'login/input',
    'font-awesome.min',
    'plugins/bootstrap-tagsinput',
), null, array('inline' => false));

$submit_url = array(
    'controller' => 'users',
    'action' => 'signup'
);
$this->set('out_breadcrumb',true);
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>

<?php $other_data = $this->data;?>
<div id="content" class="clearfix">
  <div class="boxLogo">
    <p><img src="/img/common/login/imglogoLogin.png" width="123" height="57" alt="teratail" /></p>
    <h2>Trang Q&amp;A dành cho các lập trình viên</h2>
  </div>

<?php
if(!empty($this->request->params['ref'])){
    $submit_url['ref'] = $this->request->params['ref'];
}
?>
  <form action="<?php echo $this->Html->url($submit_url); ?>" class="form-horizontal margin-none" id="UserSignupForm" method="post" accept-charset="utf-8">
    <div id="boxForm" class="js-validation">
      <p class="ttlSub ttlSub_bkgBk">Đăng ký thành viên mới</p>
<?php

    echo '<div class="boxError">';
    echo $this->Form->error('TmpUser.display_name', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.mail_address', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.password', null,array('wrap' => 'p','class' => 'txtError'));
    echo $this->Form->error('TmpUser.repeat_password', null,array('wrap' => 'p','class' => 'txtError'));
    echo '</div>';
?>
      <ul>
        <li class="boxLabelInput">
          <p class="ttlInput">Tên đăng nhập (chữ hoặc số hoặc các dấu "-" "_" "." và từ 3 ~ 15 ký tự)</p>
          <div>
              <?php echo $this->Form->input(
                  'TmpUser.display_name',
                  array(
                      'type' => 'text',
                      'id' => 'name',
                      'class' => 'mod-inputField mod-inputField-max',
                      'div' => false,
                      'label' => false,
                      'error' => false,
                      'maxlength' =>"15",
                  )
              ); ?>
          </div>
        </li>
        <li class="boxLabelInput">
          <p class="ttlInput">Địa chỉ mail</p>
          <div>
              <?php echo $this->Form->input(
                    'TmpUser.mail_address',
                    array(
                        'type' => 'text',
                        'id' => 'mail_address',
                        'class' => 'mod-inputField mod-inputField-max',
                        'div' => false,
                        'label' => false,
                        'error' => false,
                    )
              ); ?>
          </div>
        </li>
        <li class="boxLabelInput">
          <p class="ttlInput">Mật khẩu (từ 6 ~ 20 ký tự và không được sử dụng ký hiệu)</p>
          <div>
              <?php echo $this->Form->input(
                  'TmpUser.password',
                  array(
                      'type' => 'password',
                      'id' => 'password',
                      'class' => 'mod-inputField mod-inputField-max',
                      'div' => false,
                      'label' => false,
                      'error' => false,
                  )
              ); ?>
          </div>
        </li>
        <li class="boxLabelInput">
          <p class="ttlInput">Xác nhận lại mật khẩu</p>
          <div>
              <?php echo $this->Form->input(
                  'TmpUser.repeat_password',
                  array(
                      'type' => 'password',
                      'id' => 'password_check',
                      'class' => 'mod-inputField mod-inputField-max',
                      'div' => false,
                      'label' => false,
                      'error' => false,
                  )
              ); ?>
          </div>
        </li>
      </ul>
      <?php
        //unset shown data
        unset($other_data['TmpUser']);

        //hidden data is here
        foreach($other_data as $index => $fields){
            if(is_array($fields)){
                foreach($fields as $field => $value){
                    if(is_array($value)){
                        if(is_numeric($field)){
                            foreach($value as $f => $v){
                                echo $this->Form->hidden("{$index}.{$field}.{$f}");
                            }
                        }
                    }else{
                        echo $this->Form->hidden("{$index}.{$field}");
                    }
                }
            }
        }
      ?>
      <p class="txtInput">Sau khi xác nhận <a href="/legal" target="_blank">Điều khoản sử dụng</a>, và <a href="/privacy" target="_blank">Chính sách bảo mật thông tin cá nhân</a> nếu như bạn đồng ý thì vui lòng nhấn "Đồng ý đăng ký". </p>
      <div>
        <button type="submit" id="save" class="mod-btn mod-btnRegister mod-icn l-btnSignup-center" value="Đồng ý đăng ký" disabled>Đồng ý đăng ký</button>
      </div>
    </div>
  </form>
</div>
<div class="clearfix boxPrivacy">
  <p class="floatL"><a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/common/imgPrivacy.gif" alt="Privacy mark" /></a></p>
  <p class="txtInput">Xin vui lòng thiết lập nhận mail có tên miền là "@leverages.jp".<br> Trường hợp chưa cài đặt, những mail quan trọng của hệ thống teratail cũng như những liên lạc từ phía công ty chúng tôi có thể sẽ không gửi đến được địa chỉ mail của bạn</p>
</div>
<?php
$this->assign('body_id', 'pageID_mypage');
echo $this->Html->css(
  array(
    'users/users',
    'login/input',
  ),
  null,
  array('inline' => false)
);
echo $this->Html->script(
  array(
    'frontend/users/connections',
    'frontend/users/search',
  ),
  array('inline' => false)
);
?>
<div id="content" class="clearfix">
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
  <?php
  echo $this->Form->create('User', array(
    'inputDefaults' => array(
    'label' => false,
    'div' => false,
    "required" => false,
    'id' => 'UserSignupForm'
    )
  ));
  ?>
    <p class="mod-ttl">Đăng ký địa chỉ mail</p>
    <div id="boxForm" class="boxForm--social js-validation">
      <ul class="boxList--social">
        <li class="boxLabelInput boxLabelInput--social clearfix">
          <dl class="ttlInput-wrap">
            <dd class="boxInput--social">
                <?php
                echo $this->Form->input('mail_address', array(
                  'type' => 'text',
                  'maxlength' => 50,
                  'label' => false,
                  'id' => "mail_address",
                  'class' => 'mod-inputField mod-inputField-medium',
                  'error' => false,
                ));
                ?>
            </dd>
          </dl>
        </li>
      </ul>
      <p class="txtInput txtInput--social">Sau khi xác nhận <a href="/legal" target="_blank">Điều khoản sử dụng</a>, và <a href="/privacy" target="_blank">Chính sách bảo mật thông tin cá nhân</a> nếu như bạn đồng ý thì hãy nhấn "Đăng ký". </p>
      <div>
        <button type="submit" id="save" class="mod-btn mod-btnSignup mod-btnSubmit l-btnSignup-center" value="Đăng ký" disabled>Đăng ký</button>
      </div>
    </div>
  <?php
  echo $this->Form->end();
  ?>
</div>
<div class="clearfix boxPrivacy">
  <p class="floatL"><a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/common/imgPrivacy.gif" alt="Privacy mark"></a></p>
  <p class="txtInput">Xin vui lòng thiết lập nhận mail có tên miền là "@leverages.jp".<br>Trường hợp chưa cài đặt, những mail quan trọng của hệ thống teratail cũng như những liên lạc từ phía công ty chúng tôi có thể sẽ không gửi đến được địa chỉ mail của bạn.</p>
</div>

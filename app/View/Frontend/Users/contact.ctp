<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
    <?php if($actionChange) {?>
<li>Nhập nội dung cần liên hệ</li>
    <?php } else { ?>
<li>Hoàn thành liên hệ</li>
    <?php } ?>
<?php $this->end();?>

<?php 
$this->assign('body_id','pageID_contact');

if ($actionChange) :
$this->Html->css(array('users/setting',
), null, array('inline' => false));
?>
<div id="mainContainer">
  <h1 class="ttlMain">Nhập nội dung liên hệ</h1>
    <section class="boxPost">
<?php echo $this->Form->create('Contact', array('inputDefaults' => array(
        'label' => false,
        'div' => false,
        "required" => false,
    ),
    'type' => 'file'
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
        <dt>Địa chỉ mail*</dt>
        <dd>
          <?php echo $this->Form->input(
            'mail_address', array(
              'type' => 'text',
              'class' => 'input-block-level',
              'maxlength' => 50,
              'error' => false,
            )
          );?>
        </dd>
        <dt>Chủ đề liên hệ*</dt>
        <dd>
        <?php
        echo $this->Form->input(
                'subject', array(
                    'class' => '',
                    'type' => 'select',
                    'options' => $dataSubject,
                    'empty' => '',
                    'error' => false,
        ));
        ?>
        </dd>
        <dt>Tiêu đề*</dt>
        <dd>
        <?php
        echo $this->Form->input(
                'title', array(
                    'type' => 'text',
                    'maxlength' => 50,
                    'class' => 'input-block-level',
                    'error' => false,
                )
        );
        ?>
          <span>không quá 50 ký tự</span>
        </dd>
        <dt>Chi tiết*</dt>
        <dd class="txtBody">
          <?php
          echo $this->Form->input(
                  'content', array(
                      'type' => 'textarea',
                      'id' => 'question-editor',
                      'class' => 'input-block-level',
                      'rows' => 6,
                      'maxlength' => 1000,
                      "style" => "resize:none;",
                      'error' => false,
                  )
          );
          ?>
          <span>Vui lòng nhập chi tiết của nội dung cần liên hệ</span>
        </dd>
        <p class="txtAttention clearFix">
          <span class="left">Sau khi xác nhận về <a href="/legal/" target="_blank">Điều khoản sử dụng</a>, và <a href="/privacy/" target="_blank">Chính sách bảo mật thông tin cá nhân</a><br>
          Hãy nhấn "Đồng ý và gửi" nếu bạn đồng ý với những điều khoản của công ty</span>
          <span class="right"><a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/contact/imgPrivacy.gif" width="75" height="75" alt="privacy"></a></span>
        </p>

        <button type="submit" class="mod-btn mod-btnSend mod-icn l-btnSend" value="Đồng ý và gửi">Đồng ý và gửi</button>
      </dl>
      <?php echo $this->Form->end(); ?>
    </section>

  </div>
  <div id="sideContainer" class="l-sideContent">
    <section class="boxPostRule">
      <h2 class="ttlSub">Các chú ý khi gửi liên hệ</h2>
      <ul>
        <li>* là những mục bắt buộc, cần phải nhập.</li>
        <li>Sau khi nhập xong tất cả các mục cần thiết, hãy nhấn 「Đồng ý và gửi」 để gửi liên hệ.</li>
        <li>Sau khi nhấn, yêu cầu của bạn sẽ được gửi đến chúng tôi ngay lập tức, vì vậy hãy xác nhận kỹ về nội dung liên hệ trước khi gửi nhé.</li>
      </ul>
    </section>
  </div>
</div>

<?php else : ?>
<?php
$this->Html->css(array('login/comp',
), null, array('inline' => false));
?>
<h1 class="ttlMain">Hoàn tất liên hệ</h1>
<section class="boxPostComp">
<p>Nội dung liên hệ của bạn đã được gửi.<br>
Nếu không nhận được mail thông báo hoàn thành liên hệ sau 30 phút hoặc mail trả lời từ phía chúng tôi sau một tuần, vui lòng liên hệ lại với chúng tôi theo địa chỉ mail:</p>
<p class="txtAttention">
E-mail: <a href="mailto:info-teratail-vn@leverages.jp">info-teratail-vn@leverages.jp</a><br>
Công ty Leverages Việt Nam     Nhóm vận hành teratail
</p>
</section>
</div>
<?php endif; ?>

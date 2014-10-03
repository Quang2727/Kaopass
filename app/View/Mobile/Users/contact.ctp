<?php $this->Html->css('sp/login/input', null, array('inline' => false)); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->assign('body_id','pageID_contact'); ?>

<div class="content">

<?php if ($actionChange) : ?>

    <div class="boxForm">
      <h2 class="ttlMain">Nhập thông tin liên hệ</h2>
      <section class="boxPost">
        <p class="txtDetail">Dấu*là những thông tin cần điền.<br>
        Sau khi bạn đã nhập các mục thông tin cần thiết, hãy nhấn nút "Đống ý gửi"<br>
        Sau khi nhấn gửi, một request sẽ được gửi đến, bạn vui lòng xác nhận thông tin cẩn thận trước khi gưi. </p>
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
      <ul class="boxForm__listBlock">
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Địa chỉ Email*</p>
          <?php echo $this->Form->input(
            'mail_address', array(
              'type' => 'text',
              'class' => 'input-block-level inputSize-xlarge',
              'maxlength' => 50,
              'error' => false,
            )
          );?>
        </li>
        <li class="boxForm__listBlock__list">
            <p class="ttlInput">Nội dung liên hệ*</p>
        <?php
        echo $this->Form->input(
                'subject', array(
                    'class' => 'inputSize-xlarge',
                    'type' => 'select',
                    'options' => $dataSubject,
                    'empty' => '',
                    'error' => false,
        ));
        ?>
        </li>
        <li class="boxForm__listBlock__list">
            <p class="ttlInput">Tiêu đề*　<span class="ttlInputCap">Dưới 50 ký tự</span></p>
        <?php
        echo $this->Form->input(
                'title', array(
                    'type' => 'text',
                    'maxlength' => 50,
                    'class' => 'input-block-level inputSize-xlarge',
                    'error' => false,
                )
        );
        ?>
        </li>
        <li class="boxForm__listBlock__list">
            <p class="ttlInput">Chi tiết*</p>
        <div class="txtBody">
          <?php
          echo $this->Form->input(
                  'content', array(
                      'type' => 'textarea',
                      'id' => 'question-editor',
                      'class' => 'input-block-level inputSize-xlarge',
                      'rows' => 6,
                      'maxlength' => 1000,
                      'placeholder' => 'Vui lòng nhập chi tiết nội dung liên hệ',
                      "style" => "resize:none;",
                      'error' => false,
                  )
          );
          ?>
          </div>
        </li>
      </ul>
      <div class="privacyAttention clearFix">
        <figure class="privacyImg"><a href="http://privacymark.jp/" rel="nofollow" target="_blank"><img src="/img/contact/imgPrivacy.gif" alt="Bảo mật"></a></figure>
        <p class="txtAttention"><a href="/legal/" target="_blank">Điều khoản sử dụng</a>, và<a href="/privacy/" target="_blank">Xử lý thông tin cá nhân</a>Sau khi đã tìm hiểu và xác nhận các điểm cơ bản về, nếu bạn đồng ý với các điều khoản này, vui lòng nhấn nút "Đồng ý gửi"</p>
      </div>

      <div class="submitBtn-up">
        <input type="submit" value="Gửi đi" class="submitBtn submitBtn-large">
      </div>
      <?php echo $this->Form->end(); ?>
    </section>
  </div>

<?php else : ?>

  <div class="boxForm">
      <h2 class="ttlMain">Hoàn thành việc liên hệ</h2>
      <section class="boxPostComp">
        <p>Đã gửi nội dung liên hệ<br>
        Trường hợp sau một thời gian ngắn mà bạn không nhận được email, hay sau 1 tuần mà vẫn không nhận được liên lạc từ phía chúng tôi, bạn vui lòng liên lạc với bộ phận sau đây</p>
        <p class="txtAttention">
        E-mail: <a href="mailto:info@teratail.com">info@teratail.com</a><br>
        Công Ty Cổ Phần Leverages  Nhóm điều hành teratail
        </p>
      </section>
  </div>

<?php endif; ?>

</div>
<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css(array('sp/login/input','sp/users/setting.css'), null, array('inline' => false));

$this->assign('body_id', 'pageID_social');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>

<!-- ------------------ Main start -------------------  -->
<div class="content">
    <div class="boxForm">
          <h2 class="ttlMain">Thay đổi thông tin</h2>
          <nav class="navTabs-up">
              <ul class="navTabs navTabs-full">
                  <li><a href="/users/setting/password" class="sidebar">Thay đổi mật khẩu</a></li>
                  <li><a href="/users/setting/social" class="sidebar">Thiết lập liên kết SNS</a></li>
              </ul>
          </nav>
          <section class="boxPost">
              <h3 class="ttlRule">Trường hợp bạn thay đổi địa chỉ email</h3>
              <p class="txtDetail">Chúng tôi sẽ gửi email xác nhận đến bạn<br>
                Hoàn thành việc thay đổi thông tin từ URL có trong email đã nhận. URL này<span class="txtBold">sau 30 phút sẽ</span>không sử dụng được.</p>
        <?php
        echo $this->Form->create('User', array(
          'inputDefaults' => array(
            'label' => false,
            'div' => false,
            "required" => false
          ),
          'type' => 'file'
        ));
                    ?>
                    <div class="boxError">
                    <?php
                        if(isset($errors)) {
                            echo '<div class="boxError">';
                            foreach($errors as $error) {
                                echo '<p class="txtError">'.$error['0'].'</p>';
                            }
                            echo '</div>';
                        }
        ?>
                    </div>
        <ul class="boxForm__listBlock">
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Địa chỉ email*　<span class="ttlInputCap">không quá 50 ký tự</span></p>
          <?php
            echo $this->Form->input('mail_address', array(
              'type' => 'text',
              'maxlength' => 50,
              'label' => false,
              'error' => false,
              'class' => 'inputSize-xlarge',
            ));
          ?>
          </li>
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">icon</p>
    <?php /*
            <canvas id="icnUserThumb" ></canvas><div></div>
    */
        $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$User['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$User['photo'];
    ?>
              <div class="boxFile">
                  <figure id="icnThumbFile" class="icnThumb"><?php echo $this->Html->image($img_src, array(
                      'alt' => $User['display_name'],
                      'class' => "icnUserThumb",
                      'id' => 'crop_result',
                      'error' => false,
                    ));
                  ?></figure>
                  <div id="boxFileSelect">
                    <input id="btnFile" class="btnFilechenge" onclick="$('#txtFile_input').click();" value="Thay đổi hình ảnh" type="button">
                    <input id="txtFake_input_file" readonly="" value="jpg、png、gif định dạng（dưới500KB）" type="text" style="width:200px;">
                    <input id="txtFile_input" onchange="$('#txtFake_input_file').val($(this).val().replace('C:\\fakepath\\',''));$('#FileClear').css('display','inline');" type="file" name="data[User][photo]" style="display:none;">
                    <a id="FileClear" href="#" onclick="$('#txtFile_input').val('');$('#txtFake_input_file').val('jpg、png、gif định dạng（dưới500KB）');$('#FileClear').css('display','none');return false;" style="display:none;">Xóa</a>
                  </div>
              </div>
          </li>
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Thuộc về</p>
              <?php
                echo $this->Form->input('department', array(
                  'type' => 'text',
                  'label' => false,
                  'placeholder' => 'Đang thuộc về tổ chức, doanh nghiệp',
                  'maxlength' => 50,
                  'error' => false,
                  'class' => 'inputSize-xlarge',
                ));
              ?>
          </li>
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Nơi ở</p>
              <?php
                echo $this->Form->input('prefecture_cd', array(
                  'type' => 'select',
                  'options' => $dataPre,
                  'error' => false,
                  'class' => 'inputSize-medium',
                ));
              ?>
          </li>
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Giới thiệu về bản thân　<span class="ttlInputCap">dưới 300 ký tự</span></p>
              <?php
                echo $this->Form->input('self_info', array(
                  'type' => 'textarea',
                  'maxlength'=>300,
                  'rows' => 6,
                  "style" => "resize:none;",
                  'label' => false,
                  'error' => false,
                  'class' => 'inputSize-xlarge',
                ));
              ?>
          </li>
          <li class="boxForm__listBlock__list">
              <p class="ttlInput">Site・Blog　<span class="ttlInputCap">dưới 100 ký tự</span></p>
              <?php
                echo $this->Form->input('blog', array(
                  'type' => 'text',
                  'maxlength' => 100,
                  'label' => false,
                  'error' => false,
                  'class' => 'inputSize-xlarge',
                ));
              ?>
          </li>
        </ul>

        <div class="submitBtn-up">
          <!--a class="a_submit" href="#"><img src="/img/users/setting/btnSend.jpg" alt="Lưu"></a-->
          <?php
            echo $this->Form->input('Lưu', array(
              'type' => 'button',
              'alt' => 'Lưu',
              'value' => 'Lưu',
              'class' => 'submitBtn submitBtn-large',
            ));
          ?>
        </div>
        <?php
        echo $this->Form->end();
        ?>
      </section>
    </div>
</div>
<!-------------------- Main end ---------------------->

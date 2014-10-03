<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css('users/setting', null, array('inline' => false));
$this->Html->script('frontend/users/setting', array('inline' => false));

$this->assign('body_id', 'pageID_social');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Thay đổi thông tin</li>
<?php $this->end();?>

<!-- ------------------ Main start -------------------  -->
<div id="mainContainer">
  <h1 class="ttlMain">Thay đổi thông tin</h1>
  <section class="boxPost">
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
    <dl>
      <dt>Địa chỉ mail*</dt>
      <dd><?php
        echo $this->Form->input('mail_address', array(
          'type' => 'text',
          'maxlength' => 50,
          'label' => false,
          'error' => false,
        ));
      ?><span>không quá 50 ký tự</span></dd>

      <dt>Icon</dt>
      <dd class="boxFile">
<?php /*
        <canvas id="icnUserThumb" ></canvas><div></div>
*/
    $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$User['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$User['photo'];
?>
          <dd class="boxFile">
            <div id="icnThumbFile"><?php echo $this->Html->image($img_src, array(
            'alt' => $User['display_name'],
            'class' => "icnUserThumb",
            'id' => 'crop_result',
            'error' => false,
          ));
        ?></div>
            <div id="boxFileSelect">
              <button type="button" id="btnFile" class="mod-btn mod-btnImgChange mod-btnSubmit" onclick="$('#txtFile_input').click();" value="Thay đổi ảnh">Thay đổi ảnh</button>
              <input id="txtFake_input_file" readonly="" value="jpg、png、gif (dưới 500KB)" type="text" style="width:200px;">
              <input id="txtFile_input" onchange="$('#txtFake_input_file').val($(this).val().replace('C:\\fakepath\\',''));$('#FileClear').css('display','inline');" type="file" name="data[User][photo]" style="opacity:0;width:0px;height:0px;font-size:0" accept="image/*">
              <a id="FileClear" href="#" onclick="$('#txtFile_input').val('');$('#txtFake_input_file').val('jpg、png、gif (dưới 500KB)');$('#FileClear').css('display','none');return false;" style="display:none;">Xóa</a>
            </div>
          </dd>

      <dt>Nơi công tác</dt>
      <dd><?php
        echo $this->Form->input('department', array(
          'type' => 'text',
          'label' => false,
          'placeholder' => 'Công ty, tổ chức đang công tác hiện tại',
          'maxlength' => 50,
          'error' => false,
        ));
      ?></dd>

      <dt>Địa chỉ</dt>
      <dd><?php
        echo $this->Form->input('prefecture_cd', array(
          'type' => 'select',
          'options' => $dataPre,
          'error' => false,
        ));
      ?></dd>

      <dt>Tự giới thiệu</dt>
      <dd class="txtBody"><?php
        echo $this->Form->input('self_info', array(
          'type' => 'textarea',
          'maxlength'=>300,
          'rows' => 6,
          "style" => "resize:none;",
          'label' => false,
          'error' => false,
        ));
      ?><span>không quá 300 ký tự</span></dd>

      <dt>Site - Blog</dt>
      <dd><?php
        echo $this->Form->input('blog', array(
          'type' => 'text',
          'maxlength' => 100,
          'label' => false,
          'error' => false,
        ));
      ?><span>không quá 100 ký tự</span></dd>

    </dl>

    <?php
      echo $this->Form->input('Lưu', array(
        'type' => 'button',
        'alt' => 'Lưu',
        'value' => 'Lưu',
        'class' => 'mod-btn mod-btnSignup mod-icn l-btnSignup-center',
      ));
    ?>
    <?php echo $this->Form->end(); ?>
  </section>
</div>
<!-------------------- Main end ---------------------->

<!-------------------- Side start ---------------------->
  <div id="sideContainer" class="l-sideContent"> 
    <!---- ▼設定ボタン start ---->
    <nav>
      <ul class="boxSettingSelect">
        <li class="sidebar_active">Thay đổi thông tin</li>
        <?php if ($user['mail_confirm_flag']): ?><li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'password')) ?>" class="sidebar">Thay đổi mật khẩu</a></li><?php endif; ?>
        <li><a href="/users/setting/social" class="sidebar">Thiết lập liên kết SNS</a></li>
        <section class="boxPostRule">
          <h2 class="ttlSub">Lưu ý khi thay đổi thông tin</h2>
          <h3 class="ttlRule">Trường hợp thay đổi địa chỉ mail</h3>
          <p> Hệ thống sẽ gửi mail xác nhận để kiểm tra quyền sở hữu mail của bạn.
            Hãy sử dụng URL trong mail đã nhận để hoàn thành thay đổi địa chỉ mail.
            URL này <span class="txtBold">sau 30 phút sẽ không còn hiệu lực</span></p>
        </section>
      </ul>
    </nav>
    <!---- 設定ボタン end ------> 
  </div>

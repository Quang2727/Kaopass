    <div id="mainContainer">
        <h1 class="ttlMain ttlForget">Gửi mail để thay đổi mật khẩu</h1>
        <section class="boxPost">
            <p class="txtForget">Hãy nhập địa chỉ mail mà bạn có thể nhận hướng dẫn thay đổi mail từ hệ thống chúng tôi<br>
                Sau khi gửi, hệ thống sẽ gửi hướng dẫn thay đổi mật khẩu đến bạn, khi đó hãy vui lòng làm theo hướng dẫn.</p>
<?php echo $this->Form->create('User',array('inputDefaults' => array())); ?>
                <div style="display:none;">
                    <input type="hidden" name="_method" value="POST"/>
                </div>
                <dl class="mod-formGroup">
                    <dt class="mod-formGroup-label">Địa chỉ mail*</dt>
                    <dd>
                        <?php
                            if(isset($errors)) {
                                foreach($errors as $error) {
                                    echo '<p class="msgValidation" data-validated="false">'.$error['0'].'</p>';
                                }
                            }
                        ?>
                        <?php echo $this->Form->input(
                            'mail_address',
                            array(
                                'type' => 'text',
                                'id' => 'mail_address',
                                'class' => 'mod-inputField mod-inputField-medium',
                                'required' => false,
                                'label' => false,
                                'div' => false
                            )
                        ); ?>
                    </dd>
                </dl>
                <button type="submit" id="save" class="mod-btn mod-btnSignup mod-icn l-btnSignup-center" value="送信する">Gửi</button>
            </form>
        </section>
    </div>
</div>




<?php $this->start('body_id'); ?>pageID_pw<?php $this->end();?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Quên mật khẩu</li>
<?php $this->end();?>

<?php
echo $this->Html->css(array(
    'users/setting',
));
?>

<div class="content">
    <div class="boxForm">
        <h2 class="ttlMain">Gửi email cài đặt lại mật khẩu</h2>
        <section class="boxPost">
            <p class="txtDetail">Hãy nhập địa chỉ email và gửi tin đi.<br>
                Sau khi bạn gửi tin đi, chúng tôi sẽ có một email hướng dẫn cài đặt lại mật khẩu, bạn hãy làm theo hướng dẫn trong Email.</p>
<?php echo $this->Form->create('User',array('inputDefaults' => array())); ?>
                <div style="display:none;">
                    <input type="hidden" name="_method" value="POST"/>
                </div>
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
                    <p class="ttlInput">Địa chỉ email*</p>
<?php echo $this->Form->input(
                'mail_address',
                array(
                    'type' => 'email',
                    'class' => 'input-block-level inputSize-xlarge',
                    'required' => false,
                    'label' => false,
                    'div' => false
                )
            ); ?>
                </li>
            </ul>
            <p class="btnSubmitForget clearfix"><a class="a_submit floatL" id="submit_password" href="#">Gửi tin đi</a></p>
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
<li>Quên nhập mật khẩu</li>
<?php $this->end();?>

<?php
$this->Html->css(array(
    'sp/login/input',
), null, array('inline' => false));
?>

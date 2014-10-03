<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css('users/setting', null, array('inline' => false));

$this->assign('body_id', 'pageID_mail');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Thiết lập gửi mail từ hệ thống</li>
<?php $this->end();?>

<!-------------------- Main start ---------------------->
    <h1 class="ttlMain">Thiết lập gửi mail từ hệ thống</h1>
<?php if($send_flag === 1): ?>
    <p class="txt">Dưới đây là danh sách những loại mail bạn có thể tạm dừng nhận. Xin vui lòng nhấn vào nút "Ngừng nhận mail" để xác nhận.</p>
<?php else: ?>
    <p class="txtSuccess">Danh sách các loại mail</p>
<?php endif; ?>
    <section class="boxPost clearfix">
    <?php echo $this->Form->create(false, array('type' => 'post', 'action' => 'setting')); ?>
        <dl>
            <dt>Loại mail đang tạm dừng</dt>
            <dd><?php echo $infoTxt; ?></dd>
            <dt>Địa chỉ mail</dt>
            <dd><?php echo $mail_address; ?></dd>
        </dl>
        <p class="btnStop"> 
            <?php echo $this->Form->hidden('send_flag', array('value' => $send_flag));?>
            <?php echo $this->Form->hidden('mail_id', array('value' => $mail_id));?>
<?php
if($send_flag === 1) {
    $sendButton = 'Ngừng nhận mail';
} else {
    $sendButton = 'Tiếp tục nhận mail';
}
echo $this->Form->submit(
    $sendButton, array(
        'type' => 'submit',
        'class' => 'btn btn-primary',
        'id' => 'submit_question',
        'div' => false,
    )
);
?>
        </p>
        </section>
    <?php echo $this->Form->end(); ?>
    </section>
<!-------------------- Main end ---------------------->
<div id="mainContainer">

<h1 class="ttlMain ttlForget">Hoàn thành gửi mail thay đổi mật khẩu</h1>

<section class="boxPost">
<p class="txtForget">Đây là mail xác nhận và thực hiện thay đổi mật khẩu được bạn yêu cầu</p>
<ul class="txtListForget">
    <li>Mail thực hiện thay đổi mật khẩu này được gửi từ info-teratail-vn@leverages.jp, xin vui lòng xác nhận và thực hiện việc thay đổi mật khẩu của bạn <span class="txtRed">trong vòng 30 phút</span></li>
    <li>Trong trường hợp không thấy mail tới, hãy xác nhận thử ở thư mục <span class="txtRed">Mail rác</span> hay <span class="txtRed">Mail làm phiền</span> của bạn</li>
    <li>Trường hợp mail bạn vẫn không nhận được mail sau khi thực hiện tất cả thao tác trên, xin hãy liên hệ lại tại
<?php
echo $this->Html->link(
    'đây',
    '/contact/input'
);
?>.</li>
</ul>
</section>
</div>

<?php $this->start('body_id'); ?>pageID_pw<?php $this->end();?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Hoàn thành thay đổi mật khẩu</li>
<?php $this->end();?>

<?php
echo $this->Html->css(array(
    'users/setting',
));
?>
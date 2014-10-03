    <h1 class="ttlMain ttlForget">Hoàn thành gửi email đăng ký tạm thời.</h1>
    <section class="boxPost">
      <p class="txtForget">Cám ơn vì đã đăng ký</p>
      <ul class="txtListForget">
        <li>Hệ thống sẽ gửi mail xác nhận từ địa chỉ info-teratail-vn@leverages.jp, hãy xác nhận mail hoàn thành đăng ký <span class="txtBold">trong vòng 30 phút</span></li>
        <li>Trường hợp không nhận được mail, hãy kiểm tra ở thư mục <span class="txtBold">Mail làm phiền </span>và<span class="txtBold"> Mail rác</span>.</li>
        <li>Nếu sau khi đã kiểm tra những trường hợp trên nhưng vẫn không nhận được mail từ hệ thống, xin vui lòng liên hệ tại <?php
echo $this->Html->link(
    'đây',
    '/contact/input'
);
?>.</li>
      </ul>
    </section>

<?php $this->start('body_id'); ?>pageID_pw<?php $this->end();?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Hoàn thành đăng ký tạm thời</li>
<?php $this->end();?>

<?php
echo $this->Html->css(array(
    'users/setting',
    'login/comp',
));
?>

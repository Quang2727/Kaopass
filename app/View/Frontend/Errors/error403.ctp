<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>

<p class="imgUnsupport"><img src="/img/common/unsupport.png" width="182" height="182" alt="Sorry."</p>
<div class="txtUnsupport">
    <p>Chúng tôi thành thật xin lỗi nhưng hệ thống không hỗ trợ truy cập từ môi trường của bạn (browser, vị trí).<br />
    Chỉ những môi trường dưới đây hiện tại được hệ thống teratail hỗ trợ.</p>
    <ul class="boxListUnsupport">
        <li class="listUnsupport">Vị trí trong nước Việt Nam</li>
        <li class="listUnsupport">
            Brwoser
            <ul>
                <li>Google Chrome phiên bản mới nhất</li>
                <li>Firefox　phiên bản mới nhất</li>
                <li>Safari　phiên bản mới nhất</li>
                <li>Internet Explorer phiên bản mới nhất</li>
            </ul>
        </li>
    </ul>
    <p>Mọi thắc mắc xin hãy liên hệ info-teratail-vn@leverages.jp</p>
</div>


<?php
echo $this->Html->css(array(
    'code/style',
));
?>

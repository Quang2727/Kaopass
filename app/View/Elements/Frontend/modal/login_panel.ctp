<?php
$social_tag = array('Facebook' => 'btnLogin',
                    'Twitter' => 'btnLogin',
                    'Google' => 'btnLogin',
                    'Github' => 'btnLoginLast',
                    // 'Hatena' => 'btnLoginLast'
);
?>
<div id="bkgModalLogin">
</div>
<div id="boxModalLogin">
<section id="boxLogin">
    <div class="boxInner clearFix">
        <p class="ttlModalLogin">Đăng nhập / Đăng ký</p>
        <div class="floatL boxLeft">
            <?php
            foreach($social_tag as $brand => $class_tag):
            ?>
            <p class="<?php echo $class_tag; ?>">
                <?php
                $brand_lowercase = strtolower($brand);
                echo $this->Html->link(
                    '<img src="/img/login/btnLogin'.$brand.'.png" alt=Đăng nhập bằng "'.$brand.' width="260" height="41" class="imgHover">',
                    array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                    array('escape' => false)
                ); ?>
            </p>
            <?php endforeach; ?>
        </div>
        <div class="floatR boxRight l-boxLogin">
            <h2>Đăng nhập bằng teratail</h2>
            <?php echo $this->Element('Frontend/forms/login'); ?>
        </div>
        <p class="txtAboutLink"><a href="/about"> Để hiểu thêm thông tin về teratail, nơi các lập trình viên trao đổi kiến thức</a></p>
        <button class="btnClose"><img src="/img/common/btnClose.png" alt="Đóng" width="46" height="46"></button>
    </div>
</section>
</div>

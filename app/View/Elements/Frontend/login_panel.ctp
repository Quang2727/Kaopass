<section id="boxLogin">
    <div class="boxInner">
        <?php
        $social_tag = array('Facebook' => 'btnLogin',
                            'Twitter' => 'btnLogin',
                            'Google' => 'btnLogin',
                            'Github' => 'btnLoginLast',
                            //'Hatena' => 'btnLoginLast'
        );
        foreach($social_tag as $brand => $class_tag):
        ?>
        <p class="<?php echo $class_tag; ?>">
            <?php
            $brand_lowercase = strtolower($brand);
            echo $this->Html->link(
                '<img src="/img/login/btnLogin'.$brand.'.png" alt=Đăng nhập bằng "'.$brand.' width="314" height="35" class="imgHover">',
                array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                array('escape' => false)
            ); ?>
        </p>
        <?php endforeach; ?>

        <h2>Đăng nhập bằng tài khoản teratail</h2>
        <?php echo $this->Element('Frontend/forms/login'); ?>
</div>
</section>

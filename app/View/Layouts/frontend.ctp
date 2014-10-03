<!DOCTYPE html>
<html lang="vi-VN">
<head>
<?php echo $this->Element('tags/header_top', array('environment' => $environment)); ?>
<?php echo $this->Html->charset();?>
<meta name="viewport" content="initial-scale=0.2">
<?php if($this->fetch('description')) {?>
<meta name="Description" content="<?php echo h($this->fetch('description')); ?>">
<?php } ?>
<?php /* if($this->fetch('keywords')) {?>
<meta name="Keywords" content="<?php echo h($this->fetch('keywords')); ?>">
<?php } */ ?>
<meta name="robots" content="<?php echo (isset($seo_params["robots"])) ? $seo_params["robots"] : 'noindex,nofollow' ; ?>">
<?php if(isset($canonical)) { ?>
<link rel="canonical" href="<?php echo $canonical ?>">
<?php } ?>
<?php if(isset($meta_prev_link)) { ?>
<link rel="prev" href="<?php echo $meta_prev_link;?>">
<?php } ?>
<?php if(isset($meta_next_link)) { ?>
<link rel="next" href="<?php echo $meta_next_link;?>">
<?php } ?>
<link rel="alternate" hreflang="ja" href="https://teratail.com/"/>
<link rel="alternate" hreflang="vi" href="https://vn.teratail.com/"/>
<?php echo $this->fetch('meta'); ?>
<title><?php echo strip_tags($title_for_layout); ?></title>
<?php
echo $this->Html->css(array(
    'reset',
    'common_new',
    'perfect-scrollbar.min',
));
?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('html5shiv')); ?>
<![endif]-->
<?php echo $this->fetch('css');?>
<?php
echo $this->Html->script(array(
    'jquery',
    'jquery.smoothscroll',
    'perfect-scrollbar.min',
    'jquery.mousewheel',
    'common',
    'link',
    'popbox.min',
    'boxLabel',
));
?>
<script type="text/javascript">
    BASE = '<?php echo Router::url('/', true) ?>';
    USER_ID = <?php echo isset($User['id']) ? $User['id'] : 'null'; ?>;
</script>
<?php echo $this->fetch('script');?>
<?php echo $this->Element('tags/header_bottom', array('environment' => $environment)); ?>
</head>
<body id="<?php echo $this->fetch('body_id');?>">
<?php echo $this->Element('tags/body_top', array('environment' => $environment)); ?>
<?php echo $this->Session->flash('complete'); ?>

    <div><?php echo $this->Session->flash('medal'); ?></div>
    <?php
        if(isset($opinion_thanks)) $this->assign('complete_msg', 'Cảm ơn bạn đã gửi ý kiến đến chúng tôi');
        if(isset($report_thanks)) $this->assign('complete_msg', 'Cảm ơn bạn đã thông báo đến chúng tôi');
        if ($this->fetch('complete_msg')) : ?>
        <section class="boxComp">
        <p class="txtComp"><?php echo $this->fetch('complete_msg');?></p>
        </section>
    <?php endif;?>
        <?php
        //open sidemenu at startup
        $sidemenu_class = Configure::read('Site.SideInitOpen') !== true ? 'menu-hidden' : '';
        //layout type
        $layout_class = Configure::read('Site.FluidLayout') !== true ? 'fixed' : '';
        ?>
        <header id="header" class="l-header">
            <?php echo $this->Element('Frontend/header');?>
        </header>
        <?php if (isset($User) && is_null($User['mail_address'])) : ?>
        <div class="boxInfo boxInfo--unfinished boxInfo--show">
            <div class="boxInfo__inner">
                <?php echo $this->Html->link(
                    'Xác nhận địa chỉ mail',
                    array(
                        'controller' => 'users',
                        'action' => 'socialSignupMailRegister',
                    ),
                    array(
                        'class' => 'boxInfo__sendBtn mod-btn mod-btnBlue',
                    ));?>
                <p class="boxInfo__message boxInfo__message--unfinished">Hãy xác nhận địa chỉ mail để có thể sử dụng toàn bộ chức năng của hệ thống teratail.</p>
            </div>
        </div>
        <?php elseif (isset($User['mail_confirm_flag']) && $User['mail_confirm_flag'] === 0) : ?>
        <div class="boxInfo boxInfo--unfinished boxInfo--show">
            <div class="boxInfo__inner">
                <button class="boxInfo__sendBtn mod-btn mod-btnBlue">Gửi lại mail xác nhận</button>
                <p class="boxInfo__message boxInfo__message--unfinished">Chúng tôi đã gửi mail xác nhận đến địa chỉ mail bạn vừa nhập. Hãy hoàn thành việc xác nhận mail bằng cách nhấn vào URL có trong mail. Xin lưu ý URL của mail chỉ có hiệu lực trong vòng 30 phút.</p>
            </div>
        </div>
        <div class="boxInfo boxInfo--sent boxInfo--hide">
            <div class="boxInfo__inner">
                <p class="boxInfo__message boxInfo__message--sent">Đã gửi mail xác nhận. Hãy hoàn thành việc xác nhận mail bằng cách nhận vào URL có trong mail <br>
                Trường hợp không nhận được mail xin liên hệ tại <?php echo $this->Html->link('đây', array('controller' => 'contact', 'action' => 'input'));?></p>
            </div>
        </div>
        <div class="boxInfo boxInfo--error boxInfo--hide">
            <div class="boxInfo__inner">
                <button class="boxInfo__sendBtn mod-btn mod-btnBlue">Gửi lại mail xác nhận</button>
                <p class="boxInfo__message boxInfo__message--unfinished"><strong class="boxInfo__message--bold">Lổi:</strong>Hệ thống không thể gửi mail xác nhận đến địa chỉ của mail của bạn. Xin vui lòng thử lại lần nữa.</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="row-fluid">
            <?php echo $this->Session->flash(); ?>
        </div><!-- /flash message -->
        <div id="container" class="l-container clearfix">
            <?php if (!isset($out_breadcrumb) || $out_breadcrumb !== true ) : ?>
            <div id="breadcrumb">
            <ul>
            <li><a href="/">Trang chủ</a></li>
            <?php echo $this->fetch('breadcrumb'); ?>
            </ul>
            </div>
            <?php endif; ?>
            <div id="notify">
                <?php echo $this->Session->flash('notify'); ?>
            </div><!-- /Notify -->

            <!-- Old browsers warning -->
            <?php echo $this->Element('oldbrowsers'); ?>

            <!-- No javascript support -->
            <?php echo $this->Element('noscript'); ?>

            <!-- Main contents -->

            <?php echo $this->fetch('content'); ?>
        </div>

        <footer id="footer">
            <?php echo $this->Element('Frontend/footer');?>
        </footer>

        <?php
        if(!isset($User)){
            echo $this->Element('Frontend/modal/login_panel');
        }
        ?>
        <div id="debugger">
            <?php echo $this->element('sql_dump'); ?>
        </div><!-- /debugger -->

        <!-- scripts holder -->
        <?php
        //JS libraries
        $this->Html->script(array('vendor/bootstrap.min'), array('inline' => false, 'block' => 'scriptBottom'));

        //Social network configs
        echo $this->Element('socialnetworks');

        //plugins
        $this->Html->script(array(
            'plugins/lazyload',
            'plugins/jquery.notyfy'
        ), array('inline' => false, 'block' => 'scriptBottom'));

        //user defined scripts
        $this->Html->script(array(
            'frontend/main',
            'frontend/plugins',
            'frontend/common',
        ), array('inline' => false, 'block' => 'scriptBottom'));

        //fetch scripts
        echo $this->fetch('scriptBottom');
        echo $this->Element('tags/body_bottom', array('environment' => $environment));
        echo $this->Element('ad_tags/forever');
        if (isset($conversion_tag_flag) == true && $conversion_tag_flag == 1) {
          echo $this->Element('ad_tags/conversion');
        }
        ?>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-53362400-1', 'auto');
            ga('send', 'pageview');
        </script>
        <!-- /scripts holder -->

    </body>
</html>

<!DOCTYPE html>
<html lang="vi-VN">
<head>
<?php echo $this->Element('tags/header_top', array('environment' => $environment)); ?>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=0.2">
<meta name="Description" content="<?php echo $this->fetch('description'); ?>">
<?php /*
<meta name="Keywords" content="<?php echo $this->fetch('keywords'); ?>">
*/ ?>
<?php if(isset($seo_params["robots"])) { ?>
<meta name="robots" content="<?php echo $seo_params["robots"]; ?>">
<?php } ?>
<?php if(isset($canonical)) { ?>
<link rel="canonical" href="<?php echo $canonical ?>">
<?php } ?>
<?php echo $this->fetch('meta'); ?>
<link rel="alternate" hreflang="ja" href="https://teratail.com/"/>
<link rel="alternate" hreflang="vi" href="https://vn.teratail.com/"/>
<title><?php echo strip_tags($title_for_layout); ?></title>
<?php
echo $this->Html->css(array(
    'reset',
    'common_new',
    'popbox',
    'style',
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
    'jquery.smoothscroll.js',
    'common',
    'popbox.min.js',
    'perfect-scrollbar.min',
    'jquery.mousewheel',
    'frontend/common',
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

  <?php
    if(isset($opinion_thanks)) $this->assign('complete_msg', 'Cảm ơn bạn đã ý kiến đến chúng tôi');
    if(isset($report_thanks)) $this->assign('complete_msg', 'Cảm ơn bạn đã thông báo đến chúng tôi');
    if ($this->fetch('complete_msg')) : ?>
    <section class="boxComp">
    <p class="ttlMain"><?php echo $this->fetch('complete_msg');?></p>
    </section>
  <?php endif;?>
  <header id="header" class="l-header">
      <div class="boxInner boxInner_static clearfix">
        <p id="ttlHeader_static"><a href="/">
          <?php echo $this->Html->image('common/ttlHeader.png', array(
              "alt" => "teratail",
              "width" => "132",
              "height" => "40"
          )); ?>
        </a></p>
      </div>
  </header>

  <div id="container" class="clearfix">
      <?php if (!isset($out_breadcrumb) || $out_breadcrumb !== true ) : ?>
      <div id="breadcrumb">
      <ul>
      <li><a href="/">Trang chủ teratail</a></li>
      <?php echo $this->fetch('breadcrumb'); ?>
      </ul>
      </div>
      <?php endif; ?>
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

    //Social network configs
    echo $this->Element('socialnetworks');

    //plugins
    echo $this->Html->script(array(
      'plugins/lazyload',
      'plugins/jquery.notyfy'
    ));

    //fetch scripts
    echo $this->fetch('scriptBottom');
    echo $this->Element('tags/body_bottom', array('environment' => $environment));

    echo $this->Element('ad_tags/forever');
    if (isset($conversion_tag_flag) == true && $conversion_tag_flag == 1) {
      echo $this->Element('ad_tags/conversion');
    }
    ?>
    <!-- /scripts holder -->
  </body>
</html>

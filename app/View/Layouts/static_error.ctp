<!DOCTYPE html>
<html lang="vi-VN">
<head>
<?php echo $this->Element('tags/header_top', array('environment' => $environment)); ?>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=0.2">

<meta name="robots" content="noindex,nofollow">
<?php echo $this->fetch('meta'); ?>
<link rel="alternate" hreflang="ja" href="https://teratail.com/"/>
<link rel="alternate" hreflang="vi" href="https://vn.teratail.com/"/>
<title><?php echo strip_tags($title_for_layout); ?></title>
<?php
echo $this->Html->css(array(
    'reset',
    'common',
));
?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('html5shiv')); ?>
<![endif]-->
<?php echo $this->fetch('css');?>
<?php echo $this->Html->script(array('jquery')); ?>
<script type="text/javascript">
  BASE = '<?php echo Router::url('/', true) ?>';
  USER_ID = <?php echo isset($User['id']) ? $User['id'] : 'null'; ?>;
</script>
<?php echo $this->fetch('script');?>
<?php echo $this->Element('tags/header_bottom', array('environment' => $environment)); ?>
</head>
<body>
<?php echo $this->Element('tags/body_top', array('environment' => $environment)); ?>

  <header id="header" class="l-header">
    <div class="boxInner clearfix">
      <p id="ttlHeader">
        <?php echo $this->Html->image('common/ttlHeader.png', array(
            "alt" => "teratail",
            "width" => "84",
            "height" => "15"
        )); ?>
      </p>
    </div>
  </header>

  <div id="container">
      <?php echo $this->fetch('content'); ?>
  </div>

  <footer id="footer">
    <div id="footerBtm">
      <div class="boxInner">
        <small>&copy; <?php echo date('Y');?> Leverages Co., Ltd.</small>
      </div>
    </div>
  </footer>

    <!-- scripts holder -->
    <?php

    //Social network configs
    echo $this->Element('socialnetworks');

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

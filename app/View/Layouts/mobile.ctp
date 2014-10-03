<!DOCTYPE html>
<html lang="vi-VN">
<head>
<?php echo $this->Element('tags/header_top', array('environment' => $environment)); ?>
<?php echo $this->Html->charset();?>
<?php
if (in_array($this->Html->url(null, false), Configure::read('without_scalability'))) {
?><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1"><?php
} else {
?><meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1, maximum-scale=1.6"><?php
}
?>
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
<?php echo $this->fetch('meta'); ?>
<link rel="alternate" hreflang="ja" href="https://teratail.com/"/>
<link rel="alternate" hreflang="vi" href="https://vn.teratail.com/"/>
<title><?php echo strip_tags($title_for_layout); ?></title>
<?php echo $this->Html->css(array('sp/common')); ?>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('html5shiv')); ?>
<![endif]-->
<?php echo $this->fetch('css');?>
<?php
echo $this->Html->script(array(
    'jquery',
    'sp/common',
));
?>
<script type="text/javascript">
    BASE = '<?php echo Router::url('/', true) ?>';
    USER_ID = <?php echo isset($User['id']) ? $User['id'] : 'null'; ?>;
</script>
<?php echo $this->fetch('script');?>
<?php echo $this->Element('Mobile/tags/header_bottom', array('environment' => $environment)); ?>
</head>
<body id="<?php echo $this->fetch('body_id');?>">
  <header class="l-header" id="header">
  <?php if (isset($User['id']) === false) { ?>
    <div class="l-header__onBtnLayout clearfix">
      <h1 class="l-header__logo l-header__logo--floatL"><a href="/" title="teratailトップ"><img alt="teratail" src="/img/sp/common/ttlHeader.png"></a></h1>
      <ul class="l-header__hNav">
        <li class="l-header__hNav--loginBtn"><a href="/login/request">Đăng nhập</a></li>
        <li class="l-header__hNav--registBtn"><a href="/login/input">Đăng ký mới</a></li>
        <li class="l-header__hNav--queryBtn btnModalLogin"><a href="#">Đặt câu hỏi(PC)</a></li>
      </ul>
    </div>
  <?php } else { ?>
    <h1 class="l-header__logo l-header__logo--floatL"><a href="/" title="Trang chủ teratail"><img alt="teratail" src="/img/sp/common/ttlHeader.png"></a></h1>
    <ul class="l-header__hNav">
      <li class="l-header__hNav--queryBtn"><a href="/questions/input">Đặt câu hỏi(PC)</a></li>
    </ul>
  <?php } ?>
  </header>
  <div id="container" class="l-container">
    <?php if ((!isset($out_breadcrumb) || $out_breadcrumb !== true ) && strlen($this->fetch('breadcrumb')) > 0) : ?>
    <div id="breadcrumb">
      <ul>
        <li><a href="/">Trang chủ</a></li>
        <?php echo $this->fetch('breadcrumb'); ?>
      </ul>
    </div>
    <?php endif; ?>
    <!-- Main contents -->
    <?php echo $this->fetch('content'); ?>
  </div>
  <footer id="footer" class="l-footer">
      <?php echo $this->Element('Mobile/footer');?>
  </footer>
  <?php echo $this->element('Mobile/modal_login');?>
  <div id="debugger">
      <?php echo $this->element('sql_dump'); ?>
  </div><!-- /debugger -->

  <!-- scripts holder -->
  <?php

  //fetch scripts
  echo $this->fetch('scriptBottom');
  echo $this->Element('Mobile/tags/body_bottom', array('environment' => $environment));

  echo $this->Element('ad_tags/forever');
  if (isset($conversion_tag_flag) == true && $conversion_tag_flag == 1) {
    echo $this->Element('ad_tags/conversion');
  }
  ?>
  <!-- /scripts holder -->
</body>
</html>

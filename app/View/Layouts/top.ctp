<!DOCTYPE html>
<html lang="vi-VN">
<head>
<?php echo $this->Element('tags/header_top', array('environment' => $environment)); ?>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=0.2">
<meta name="Description" content="<?php echo $this->fetch('description'); ?>">
<meta name="Keywords" content="<?php echo $this->fetch('keywords'); ?>">
<?php echo $this->fetch('meta'); ?>
<link rel="alternate" hreflang="ja" href="https://teratail.com/"/>
<link rel="alternate" hreflang="vi" href="https://vn.teratail.com/"/>
<title><?php echo strip_tags($title_for_layout); ?></title>
<!--[if lt IE 9]>
<?php echo $this->Html->script(array('html5shiv')); ?>
<![endif]-->
<?php
echo $this->Html->css(array(
    'reset',
    'common',
    'popbox',
    'style',
    'perfect-scrollbar.min',
    'join/join'
));
?>
<?php
echo $this->fetch('css');
?>

<script type="text/javascript">
    BASE = '<?php echo Router::url('/', true) ?>';
    USER_ID = <?php echo isset($User['id']) ? $User['id'] : 'null'; ?>;
</script>

<?php
echo $this->Html->script(array(
    'jquery',
    'jquery.smoothscroll.js',
    'common',
    'popbox.min',
    'perfect-scrollbar.min',
    'jquery.mousewheel',
    'join/style'
));
?>
<?php echo $this->Element('tags/header_bottom', array('environment' => $environment)); ?>
</head>
<body id="pageID_Login">
<?php echo $this->Element('tags/body_top', array('environment' => $environment)); ?>
<?php echo $this->Session->flash('complete'); ?>
<?php echo $this->fetch('content'); //メインコンテンツ ?>
<?php
if(!isset($User)){
    //ログインパネル
    echo $this->Element('Frontend/modal/login_panel');
}
?>
<div id="debugger">
    <?php echo $this->element('sql_dump'); ?>
</div><!-- /debugger -->

<!-- scripts holder -->
<?php
//JS libraries
$this->Html->script(array('vendor/bootstrap.min'), array('inline' => false));

//plugins
$this->Html->script(array(
    'plugins/lazyload',
    'plugins/bootstrap-tabdrop',
    'plugins/jquery.notyfy'
), array('inline' => false));

//user defined scripts
$this->Html->script(array(
    'frontend/main',
    'frontend/plugins',
    'frontend/common',
), array('inline' => false));

//fetch scripts
echo $this->fetch('script');
echo $this->Element('tags/body_bottom', array('environment' => $environment));
?>
<!-- /scripts holder -->
    </body>
</html>

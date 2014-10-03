<div id="footerTop">
<div class="boxInner clearfix">
    <div id="js-pagetop" class="l-footPagetop is-sticky"><a href="#container" class="js-scroll mod-footPagetop mod-icn">PAGE TOP</a></div>
<?php if (!isset($out_opinion) || $out_opinion !== true ) : ?>
      <div class="boxContact">
<?php
/*
意見投稿処理
  暫定対応なのでリファクタリングしていません
  余裕出来たら画面遷移入れずにAjax処理に切り替得てください
*/
echo $this->Form->create('Opinion', array(
    'class' => 'mailform',
    'id' => 'OpinionIndexForm',
));
?>
        <div class="error"></div>
<?php
$query = '';
$query_flg = false;
if($_SERVER['QUERY_STRING']){
    $query = '?'.$_SERVER['QUERY_STRING'];
    $query_flg = true;
}

$protocol = ($_SERVER['REMOTE_PORT'] == 443) ? 'https' : 'http';
$url = $protocol.'://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . $query;

echo $this->Form->hidden('url', array('value' => $url));
echo $this->Form->hidden('query_flg', array('value' => $query_flg));
echo $this->Form->hidden('limited_opinion', array('value' => $limitedOpinion));
echo $this->Form->input(
        'body', array(
            'type' => 'textarea',
            'div' => false,
            'label' => false,
            'placeholder' => 'Hãy cho chúng tôi nghe ý kiến của bạn về teratail',
        )
);
?>
        <p class="btnSubmit"><a href="#" id="a_submit" class="a_submit">Gửi ý kiến</a></p>
<?php echo $this->Form->end(); ?>
        <div class="boxContactInner">
            <p>Hệ thống sẽ không thực hiện giải đáp cho ý kiến được gửi.</p>
            <p>Đối với những thắc mắc cần giải đáp xin liên hệ <a href="/contact/input">tại đây</a></p>
        </div>
    </div>
<?php endif; ?>
    <div class="leftCol">
        <figure class="footLogo"><img src="/img/common/login/imglogoLogin_white.png" width="123" height="57" alt="teratail"></figure>
        <div class="boxSiteNav-group"><?php echo $this->Element('footer-links'); ?></div>
        <p class="copyright">&copy; 2014 Leverages Co., Ltd.</p>
    </div>
</div>
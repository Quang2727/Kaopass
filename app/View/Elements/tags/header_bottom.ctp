<?php echo $this->Html->script(array('jquery.gafunc.min.js')); ?>
<?php
// Google Analytics tag parametar
$is_logined = (int)isset($User['id']);
$sip = (isset($sip)) ? $sip : '';
$referers = (isset($referers)) ? $referers : '';
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  var pluginUrl = 
   '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
  _gaq.push(['_require', 'inpage_linkid', pluginUrl]);
  _gaq.push(['_setAccount', 'UA-45098004-2']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_setCustomVar', 1, 'login', '<?php echo $is_logined;?>', 2]);
  _gaq.push(['_setCustomVar', 2, 'sip', '<?php echo $sip;?>', 2]);
  _gaq.push(['_setCustomVar', 3, 'referrers', '<?php echo $referers;?>', 2]);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php echo $this->Element('tags/google_analytics', array('environment' => $environment)); ?>
<script type="text/javascript">
if (document.referrer.match(/google\.(com|co\.jp)/gi) && document.referrer.match(/cd/gi)) {
  var myString = document.referrer;
  var r        = myString.match(/cd=(.*?)&/);
  var rank     = parseInt(r[1]);
  var kw       = myString.match(/q=(.*?)&/);
  
  if (kw[1].length > 0) {
    var keyWord  = decodeURI(kw[1]);
  } else {
    keyWord = "(not provided)";
  }

  var p        = document.location.pathname;
  _gaq.push(['_trackEvent', 'RankTracker', keyWord, p, rank, true]);
  ga('send', 'event', 'RankTracker', keyWord, p, rank, true);
}
</script>
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
  _fbq.push(['addPixelId', '728073607254455']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=728073607254455&amp;ev=NoScript" /></noscript>
<?php echo $this->Element('tags/mix_panel', array('environment' => $environment)); ?>

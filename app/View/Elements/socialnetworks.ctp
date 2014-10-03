<?php /*
<?php
Configure::load('social');
$social_settings = Configure::read('Social');
?>

<?php if(isset($social_settings['providers']['Facebook']['keys']['id'])): ?>
<!-- Facebook config -->
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $social_settings['providers']['Facebook']['keys']['id']; ?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!-- /Facebook config -->
<?php endif; ?>

<?php if(isset($social_settings['providers']['Twitter']['keys']['key'])): ?>
<!-- Twitter config -->
<?php echo $this->Html->script(array('vendor/sha1.js','vendor/codebird.js')); ?>
<script>
    var cb = new Codebird;
    cb.setConsumerKey("<?php echo $social_settings['providers']['Twitter']['keys']['key']; ?>", "<?php echo $social_settings['providers']['Twitter']['keys']['secret']; ?>");
</script>
<!-- /Twitter config -->
<?php endif; ?>

<!-- share widgets -->
<script type="text/javascript">var switchTo5x=true;</script>
<?php echo $this->Html->script(array('http://w.sharethis.com/button/buttons.js')); ?>
<script type="text/javascript">stLight.options({publisher: "ur-b1764037-f892-4cc6-f47d-38b65325b787", doNotHash: true, doNotCopy: false, hashAddressBar: false});</script>
<!-- /share widgets -->

*/ ?>

<script type="text/javascript">
    $(function(){
        notyfy({
            layout: 'top',
            theme: 'default',
            type: 'alert',
            text: '<?php echo addslashes($message); ?>',
            dismissQueue: true,
            template: '<div class="notyfy_message"><span class="notyfy_text"></span>' +
                  '<div class="notyfy_close"></div></div>',
            showEffect:  function(bar) { bar.animate({ height: 'toggle' }, 500, 'swing'); },
            hideEffect:  function(bar) { bar.animate({ height: 'toggle' }, 500, 'swing'); },
            timeout: 4000,
            force: true,
            modal: false,
            closeWith: ['click'],
            buttons: false // an array of buttons
        });
    })
</script>
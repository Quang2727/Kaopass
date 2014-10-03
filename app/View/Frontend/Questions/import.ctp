<?php if(!empty($errors)) { ?>
<div class="boxError">
<?php foreach($errors as $error) { ?>
        <p class="txtError"><?php echo$error['0']; ?></p>
<?php } ?>
</div>
<?php } ?>
<?php
echo $this->Form->create('Writing', array('onsubmit' => 'return (modal($(this)) == false)'));
echo '<label>Thời gian đăng (Vui lòng nhập theo định dạng: YYYY-MM-DD)</label><br />';
echo $this->Form->input(
    'start_time', array(
        'type' => 'text',
        'error' => false,        
        'div' => false,
        'between' => false,
        'after' => false,        
        'label' => false
    )
);
echo '～';
echo $this->Form->input(
    'end_time', array(
        'type' => 'text',
        'error' => false,        
        'div' => false,
        'between' => false,
        'after' => false,  
        'label' => false
    )
);
echo $this->Form->submit(
        'Import', array(
    'type' => 'submit',
    'class' => 'btn btn-primary',
    'div' => array('class' => 'text-center'),
    'id' => 'submit_question'
        )
);
echo '<br />';
echo $this->Form->end();

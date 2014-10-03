<?php

if(!empty($tagName)) {
  $tagNameFields = $tagName;
} else {
  $tagNameFields = "Comment";
}
$nameFields = "comment";
echo $this->Form->create(
    $tagNameFields, array(
  'inputDefaults' => array(
    'label' => false,
    'div' => false,
  ),
  'class' => 'nomargin',
  'name' => 'comment_form',
    )
);
echo $this->Form->create($tagNameFields);
?>
        <!-- 編集ボタン　ここから -->
          <?php
            $form_id = 'body';
            if($type !== QUESTION_COMMENT) {
              $form_id = 'CommentInfoFormR'.$id;
            }
            echo $this->Form->input($nameFields, array(
                          'row' => 4,
                          'cols' => 30,
                          'type' => 'textarea',
                          'placeholder' => $placeholder,
                          'label' => array('class' => 'control-label','text' => ''),
                          'id' => $form_id,
                          'class' => 'content-comment',
                          'format' => array('input', 'label')));
          ?>
  <div class="error-message" style="color:#EB6233"></div>
<p class="commentTextArea" id="fComment001">
  <input type="hidden" class="id" value="<?php echo $id; ?>" />
</p>
  <p class="btnSubmit"><input type="hidden" class="comment_type" value="<?php echo $type; ?>" />
    <a class="submit-comment btnSend mod-btn mod-btnBlue" href="#">Gửi</a>
  </p>

<?php echo $this->Form->end();?>

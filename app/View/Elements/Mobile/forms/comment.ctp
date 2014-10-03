<?php
$this->Html->css(array('questions/markdown.css'), null, array('inline' => false));
echo $this->Form->create('Reply', array());
?>
<dl class="question-editor-area">
  <dd class="txtEdit">
<?php
echo $this->Form->hidden('Reply.id');
echo $this->Form->hidden('draft_type', array('value' => 'Reply'));
if (isset($comment_flg) === false) $comment_flg = 0;
echo $this->Form->hidden('question_id', array('value' => $question['Question']['id']));
echo $this->Form->input('Reply' . '.body',
        array(
            'type' => 'textarea',
            'placeholder' => $placeholder,
            'class' => 'input-block-level question-editor content-comment',
            'label' => false,
            'error' => false,
            )
);
?>
  </dd>
</dl>
<p class="txtName">

</p>
<div class="error-message" style="color:#EB6233"></div>
<p class="btnSubmit">
<a href="#" class="submit-comment mod-btn mod-btnBlue" ref="1" id="submit_question">回答を投稿する</a>
<input type="hidden" name="comment_flg" class="comment_type" value="<?php echo $comment_flg;?>" id="ReplyCommentFlg">
</p>
<?php echo $this->Form->end(); ?>
<?php
echo $this->Html->css(array(
    'plugins/bootstrap-markdown.min',
    'plugins/bootstrap-tagsinput'
));

echo $this->Html->script(array(
//    'plugins/markdown-marked',
//    'plugins/markdown-remarked',
    'frontend/question_editor',
//    'plugins/bootstrap-markdown',
    'plugins/bootstrap-tagsinput',
    'plugins/jquery.autoSave.min',
));
?>

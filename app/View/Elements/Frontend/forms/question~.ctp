<?php
$this->Html->css(array('questions/markdown.css'), null, array('inline' => false));
?>
<fieldset>
    <div class="span9">
    <?php echo $this->Form->create('Question', array(
        'onsubmit' => 'return (modal($(this)) == false)'
    )); ?>
    <?php
    echo $this->Form->hidden('Question.id');
    echo $this->Form->hidden('draft_type', array('value' => 'Question'));
    if (!empty($editQuestion))
        echo $this->Form->hidden('edit', array('value' => $editQuestion));
    if (!empty($question['Question']['id']))
        echo $this->Form->hidden('question_id', array('value' => $question['Question']['id']));
    ?>
    <div class="control-group">
        <div class="boxItemContentDetail controls text-right">
            <?php
                echo $this->Form->input(
                        'Question.display_flag', array(
                    'type' => 'hidden', //checkbox
                    'div' => array(
                        'class' => 'checkbox inline',
                    ),
                    'label' => 'Gửi câu hỏi',
                    'format' => array('input', 'label'),
                    'checked' => !empty($this->data['Question']['display_flag'])
                        )
                );
            ?>
        </div>
    </div>

    <?php
        echo $this->Form->input(
                'Question.title', array(
            'type' => 'text',
            'class' => 'input-block-level',
            'maxlength' => 100,
            'label' => array(
                'class' => 'control-label',
                'text' => 'Tiêu đề'
            ),
                )
        );
    ?>

    <?php
        if(isset($actionView)) {
            echo $this->Form->input('Question.body', 
                    array(
                        'type' => 'textarea',
                        'placeholder' => "Đáp án của bạn",
                        'class' => 'input-block-level question-editor',
                        'label' => false,
                        'error' => false,
                        )
            );
        } else {
            echo $this->Form->input('Question.body', 
                    array(
                        'type' => 'textarea',
                        'id' => 'question-editor',
                        'class' => 'input-block-level',
                        'rows' => 10,
                        'label' => array(
                                       'class' => 'control-label',
                                       'text' => "Câu hỏi của bạn"
                                   ),
                        )
              );
          }
    ?>

      <!-- プレビュー表示用エリア　ここから -->
      <dt>Xem trước</dt>
      <dd class="txtBodyPreview">
        <div class="controls">
          <p class="question-preview"></p>
        </div>
      </dd>
      <!-- プレビュー表示用エリア　ここまで -->
    <?php
        echo $this->Form->input(
                'Question.tags', array(
            'type' => 'text',
            'class' => 'input-block-level',
            'id' => 'tags-input',
            'maxlength' => 100,
            'label' => array(
                'class' => 'control-label',
                'text' => 'Tag'
            ),
            'required' => false
                )
        );
    ?>

    <?php
        if(isset($actionView)) {
            if(isset($errors)) {
                echo '<div class="boxError">';
                foreach($errors as $error) {
                    echo '<p class="txtError">'.$error['0'].'</p>';
                }
                echo '</div>';
            }
            echo $this->Form->submit(
                '/img/questions/btnAnswer.jpg', array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'div' => array('class' => 'btnSubmit2'),
                'id' => 'submit_question'
                )
            );
        } else {
            echo $this->Form->submit(
                    'Gửi bài', array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'div' => array('class' => 'text-center'),
                'id' => 'submit_question'
                    )
            );
        }
    ?>
    <?php echo $this->Form->end(); ?>
    </div>
    <?php
        echo '<div class="span3">';
        ?>
        <div id="question-editor" class="input-block-level md-input" cols="30" rows="15" style="resize: none; background:#FAFAD2;margin-top:50px;padding: 10px 10px 10px 10px; border-radius: 10px; ">
            <b>※Quy định gửi bài</b>
            <p> - Text Text Text Text Text Text Text Text Text Text</p>
            <p> - Text Text Text Text Text Text Text Text Text Text</p>
            <p> - Text Text Text Text Text Text Text Text Text Text</p>
            <p> - Text Text Text Text Text Text Text Text Text Text</p>
            <p> - Text Text Text Text Text Text Text Text Text Text</p>
        </div>
        <?php
        echo '</div>';
    ?>
</fieldset>

<?php
echo $this->Html->css(array(
    'index',
    'plugins/bootstrap-markdown.min',
    'plugins/bootstrap-tagsinput'
));

echo $this->Html->script(array(
    'plugins/markdown-marked',
    'plugins/markdown-remarked',
    'plugins/bootstrap-markdown',
    'plugins/bootstrap-tagsinput',
    'plugins/jquery.autoSave.min',
    'frontend/question_editor',
));
?>

<?php
  echo $this->Form->create('/login/input', array(
    'class' => 'form-horizontal margin-none',
    'id' => 'UserSignupForm'));

?>
      <?php
          echo '<div class="boxError">';
          echo $this->Form->error('TmpUser.display_name', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.mail_address', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.password', null,array('wrap' => 'p','class' => 'txtError'));
          echo $this->Form->error('TmpUser.repeat_password', null,array('wrap' => 'p','class' => 'txtError'));
          echo '</div>';
      ?>

      <ul class="boxForm__listBlock">
        <li class="boxForm__listBlock__list">
          <?php echo $this->Form->input(
              'TmpUser.display_name',
              array(
                  'type' => 'text',
                  'id' => 'name',
                  'class' => 'txtInputArea',
                  'div' => false,
                  'label' => false,
                  'error' => false,
                  'placeholder'=>"ユーザー名　15文字以内",
                  'maxlength' =>"15",
              )
          ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <?php echo $this->Form->input(
              'TmpUser.mail_address',
              array(
                  'type' => 'text',
                  'id' => 'mail_address',
                  'class' => 'txtInputArea',
                  'div' => false,
                  'label' => false,
                  'error' => false,
                  'placeholder' => "メールアドレス",
              )
          ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <?php echo $this->Form->input(
              'TmpUser.password',
              array(
                  'type' => 'password',
                  'id' => 'password',
                  'class' => 'txtInputArea',
                  'div' => false,
                  'label' => false,
                  'error' => false,
                  'placeholder'=>"パスワード",
              )
              ); ?>
        </li>
        <li class="boxForm__listBlock__list">
          <?php echo $this->Form->input(
                'TmpUser.repeat_password',
                array(
                    'type' => 'password',
                    'id' => 'password_check',
                    'class' => 'txtInputArea',
                    'div' => false,
                    'label' => false,
                    'error' => false,
                    'placeholder' => "パスワード（確認用）",
                )
            ); ?>
        </li>
      </ul>
      <?php
        //unset shown data
        if (isset($other_data) && is_array($other_data)) {
          unset($other_data['TmpUser']);
          //hidden data is here
          foreach($other_data as $index => $fields){
            if(is_array($fields) === false) continue;
            foreach($fields as $field => $value){
              if(is_array($value) === false){
                echo $this->Form->hidden("{$index}.{$field}");
                continue;
              }
              if(is_numeric($field) === false) continue;
              foreach($value as $f => $v){
                echo $this->Form->hidden("{$index}.{$field}.{$f}");
              }
            }
          }
        }
      ?>
  <?php echo $this->Form->end(); ?>

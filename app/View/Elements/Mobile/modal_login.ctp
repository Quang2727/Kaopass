<div id="boxModalLogin">
<?php
  echo $this->Form->create(null, array(
    'url' => 'https://'.$_SERVER['HTTP_HOST'] . '/login/request',
    'id' => 'UserSignupForm',
    'class' => 'form-horizontal margin-none',
  ));
?>
    <div class="boxForm">
      <h2 class="ttlMain ttlMain--request">このコンテンツはログイン後利用できます</h2>
      <div class="boxForm__snsBlockArea">
        <ul class="snsBlock clearfix">
        <?php foreach(array('Facebook' => 'facebook', 'Twitter' => 'twitter', 'Google' => 'google','Github' => 'GitHub'/*, 'Hatena' => 'Hatena'*/) as $brand => $name){?>
          <li class="snsBlock__list">
            <?php
            $brand_lowercase = strtolower($brand);
            echo $this->Html->link(
                '<img src="/img/sp/common/login/icnLogin' . $brand.'.png" alt="'.$brand.'でログイン" width="50" height="50">',
                array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                array('class' => '', 'escape' => false)
            );
            ?>
          </li>
        <?php } ?>
        </ul>
        <p>または</p>
      </div>
      <ul class="boxForm__listBlock">
        <li class="boxForm__listBlock__list">
          <input name="data[User][mail_address]" id="mail_address" class="txtInputArea inputSize-xlarge" placeholder="メールアドレス" type="text" required/>
        </li>
        <li class="boxForm__listBlock__list">
          <input name="data[User][password]" id="password" class="txtInputArea inputSize-xlarge" placeholder="パスワード" type="password" required/>
        </li>
      </ul>
      <div class="submitBtn-up">
        <input type="submit" id="save" class="submitBtn submitBtn-large" value="ログイン">
      </div>
      <ul class="txtLink-group">
        <li>
          <a href="/login/input" class="icoArrow icoImg-blue">新規アカウント登録はこちら</a>
        </li>
        <li>
          <a href="/login/forget/input" class="icoArrow icoImg-blue">パスワードを忘れた方</a>
        </li>
      </ul>
    </div>
<?php echo $this->Form->end(); ?>
  <button class="btnClose"><img src="/img/sp/common/btnClose.png" alt="閉じる" width="10" height="10"></button>
</div>

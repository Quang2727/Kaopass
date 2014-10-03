<?php $fullPath = $this->Html->url(null, true); ?>
<?php
$this->start('meta');
echo $this->Html->meta(array(
    'property' => 'og:title',
    'content' => strip_tags($title_for_layout),
));
echo $this->Html->meta(array(
    'property' => 'og:type',
    'content' => 'website',
));
echo $this->Html->meta(array(
    'property' => 'og:description',
    'content' => $description_for_layout,
));
echo $this->Html->meta(array(
    'property' => 'og:url',
    'content' => $fullPath,
));
echo $this->Html->meta(array(
    'property' => 'og:image',
    'content' => "https://".$_SERVER['HTTP_HOST']."/img/imgFacebookShare.png",
));
echo $this->Html->meta(array(
    'property' => 'og:site_name',
    'content' => 'teratail',
));
echo $this->Html->meta(array(
    'property' => 'og:locale',
    'content' => 'ja',
));
echo $this->Html->meta(array(
    'property' => 'fb:admins',
    'content' => '313209618817050',
));
echo $this->Html->meta(array(
    'property' => 'fb:app_id',
    'content' => '313209618817050',
));
$this->end();
?>
<?php
echo $this->Html->css(
    array(
        'index',
    ),
    null,
    array('inline' => false)
);
?>
<?php if (!isset($User)): ?>
<section class="bkgSectionLogin">
  <div class="boxSectionLogin clearfix">
    <div class="boxSectionLogin__inner">
      <div class="boxSectionLogin__about floatL">
        <p><img src="/img/common/login/imglogoLogin_white.png" width="123" height="57" alt="teratail"></p>
        <h2>Trang Q&amp;A dành cho các lập trình viên</h2>
        <p class="boxSectionLogin__about__link"><a href="/about">Xem thêm thông tin về teratail</a></p>
      </div>
      <div class="boxSectionLogin__input floatL">
        <?php
          echo $this->Form->create(null, array(
            'url' => 'https://'.$_SERVER['HTTP_HOST'] . '/login/request',
            'class' => 'form-horizontal margin-none',
            'id' => 'UserSignupForm',
          ));
        ?>
          <div class="boxForm">
            <h2 class="ttlMain ttlMain--request">Đăng nhập bằng SNS / Đăng ký mới</h2>
            <div class="boxForm__snsBlockArea">
              <ul class="snsBlock clearfix">
                <?php
                $social_tag = array('Facebook',
                                    'Twitter',
                                    'Google',
                                    'Github',
                                    // 'Hatena'
                );
                foreach($social_tag as $brand) {
                  $brand_lowercase = strtolower($brand);
                ?><li class="snsBlock__list"><?php
                  echo $this->Html->link(
                      '<img src="/img/login/icnLogin'.$brand.'.png" alt="'.$brand.'" width="54" height="58">',
                      array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                      array('escape' => false)
                  ); ?></li>
                <?php } ?>
              </ul>
              <p>hoặc là</p>
            </div>
            <h2 class="ttlMain ttlMain--request">Đăng nhập bằng teratail</h2>
            <ul class="l-formLists-login">
              <li>
                <?php echo $this->Form->input(
                    'User.mail_address',
                    array(
                        'type' => 'text',
                        'id' => 'mail_address',
                        'class' => 'mod-inputField mod-inputField-max',
                        'div' => false,
                        'label' => false,
                        'error' => false,
                        'placeholder' => "Địa chỉ mail",
                    )
                ); ?>
              </li>
              <li>
                <?php echo $this->Form->input(
                    'User.password',
                    array(
                        'type' => 'password',
                        'id' => 'password',
                        'class' => 'mod-inputField mod-inputField-max',
                        'div' => false,
                        'label' => false,
                        'error' => false,
                        'placeholder'=>"Mật khẩu",
                    )
                    ); ?>
              </li>
            </ul>
            <div>
              <button type="submit" id="save" class="mod-btn mod-btnLogin mod-icn l-btnLogin-center">Đăng nhập</button>
            </div>
            <ul class="txtLinkBlock">
              <li class="txtLinkBlock__txtLink">
                <a href="/login/input">Đăng ký tài khoản mới tại đây</a>
              </li>
              <li class="txtLinkBlock__txtLink">
                <a href="/login/forget/input">Bạn quên mật khẩu ?</a>
              </li>
            </ul>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<?php
switch ($type) {
  case SORT_MYTAG:
    $type = 'btnMytag';
    break;
  case SORT_DATE:
    $type = 'btnNew';
    break;
  case SORT_VIEW:
    $type = 'btnAttention';
    break;
  case SORT_NOT_DONE:
    $type = 'btnUnresolved';
    break;
  case SORT_DONE:
    $type = 'btnResolved';
    break;
  case SORT_UNANSWERED:
    $type = 'btnUnanswered';
    break;
}
?>
<input type="hidden" id="topLoadedTab" value="<?php echo $type; ?>" />
<div id="boxContentWrap">
  <div id="mainContainer">
    <nav>
      <ul id="tab" class="boxSelectTab clearfix">
        <li class="btnMytag<?php if($type == 'btnMytag') echo ' on';?>" data-tabname="btnMytag">
          <p><span>MyTag</span></p>
        </li>
        <li class="btnNew<?php if($type == 'btnNew') echo ' on';?>" data-tabname="btnNew">
          <p><span>Mới đăng</span></p>
        </li>
        <li class="btnAttention<?php if($type == 'btnAttention') echo ' on';?>" data-tabname="btnAttention">
          <p><span>Đáng chú ý</span></p>
        </li>
        <li class="btnUnresolved<?php if($type == 'btnUnresolved') echo ' on';?>" data-tabname="btnUnresolved">
          <p><span>Chưa giải quyết</span></p>
        </li>
        <li class="btnResolved<?php if($type == 'btnResolved') echo ' on';?>" data-tabname="btnResolved">
          <p><span>Đã giải quyết</span></p>
        </li>
        <li class="btnUnanswered<?php if($type == 'btnUnanswered') echo ' on';?>" data-tabname="btnUnanswered">
          <p><span>Chưa có trả lời</span></p>
        </li>
      </ul>
    </nav>
    <div class="boxContentWrap btnMytag"<?php if($type != 'btnMytag') echo ' style="display: none;"';?>>
<?php
if(!isset($User)) {
?>
<div class="tabNotLogin">
        <p class="tabNotItemImg"><?php echo $this->Html->image('common/imgLogoOpt.png', array('width' => '60', 'height' => '54'));?></p>
        <p>Những Q&amp;A mới nhất trong các lĩnh vực mà bạn quan tâm sẽ được hiển thị tại đây. <br>
        Sau khi đăng nhập, Hãy đăng ký MyTag để có thể sử dụng</p>
        <p>Đăng nhập - Đăng ký tài khoản mới tại <a href="#" class="btnModalLogin">đây</a></p>
    </div>
<?php
} else if (empty($myTags)) {//$first_login === true
?>
      <div class="tabNotMytag">
        <p class="tabNotItemImg"><?php echo $this->Html->image('common/imgEncourage.png', array('width' => '710', 'height' => '389'));?></p>
        <p class="txtNotMyTagNavi">Việc đăng ký "MyTag", sẽ giúp bạn có thể cập nhật được những thông tin, ngôn ngữ lập trình hay lĩnh vực mà bạn quan tâm.
Ngoài ra, dựa trên ngôn ngữ lập trình mà bạn đang quan tâm, bạn cũng có thể kết nối được với các thành viên khác có cùng sự quan tâm  giống bạn
Hãy đăng ký tại "MyTag" ở phía trên bên phải màn hình, hoặc tại <?php echo $this->Html->link('đây', '/tags');?></p>
      </div>
<?php
} else if ($type == 'btnMytag'){
  if (empty($questions)) {
          echo $this->Element('Frontend/questionlist/notfound');
  } else {
      foreach ($questions as $questionData) {
          echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
      }
      if(count($questions) >= LIMIT_QUESTION) {
          echo '<div class="feed_reload hide">1</div>';
      }

      if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
        print<<<EOF
        <div class="boxShowMore">
          <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
        </div>
EOF;
      }
  }
}
?>
      </div>
      <div class="boxContentWrap btnNew"<?php if($type != 'btnNew') echo ' style="display: none;"';?>>
<?php
if ($type == 'btnNew'){
    if (empty($questions)) {
        echo $this->Element('Frontend/questionlist/notfound');
    } else {
        echo '<ul>';
        foreach ($questions as $questionData) {
            echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
        }
        echo '</ul>';
        if(count($questions) >= LIMIT_QUESTION) {
            echo '<div class="feed_reload hide">1</div>';
        }
    }
}

if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php
}
?>
</div>
<div class="boxContentWrap btnAttention"<?php if($type != 'btnAttention') echo ' style="display: none;"';?>>
<?php
if ($type == 'btnAttention'){
    if (empty($questions)) {
        echo $this->Element('Frontend/questionlist/notfound');
    } else {
        echo '<ul>';
        foreach ($questions as $questionData) {
            echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
        }
        echo '</ul>';
        if(count($questions) >= LIMIT_QUESTION) {
            echo '<div class="feed_reload hide">1</div>';
        }
    }
}

if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php
}
?>
</div>
<div class="boxContentWrap btnUnresolved"<?php if($type != 'btnUnresolved') echo ' style="display: none;"';?>>
<?php
if ($type == 'btnUnresolved'){
    if (empty($questions)) {
        echo $this->Element('Frontend/questionlist/notfound');
    } else {
        echo '<ul>';
        foreach ($questions as $questionData) {
            echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
        }
        echo '</ul>';
        if(count($questions) >= LIMIT_QUESTION) {
            echo '<div class="feed_reload hide">1</div>';
        }
    }
}

if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php
}
?>
</div>
<div class="boxContentWrap btnResolved"<?php if($type != 'btnResolved') echo ' style="display: none;"';?>>
<?php
if ($type == 'btnResolved'){
    if (empty($questions)) {
        echo $this->Element('Frontend/questionlist/notfound');
    } else {
        echo '<ul>';
        foreach ($questions as $questionData) {
            echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
        }
        echo '</ul>';
        if(count($questions) >= LIMIT_QUESTION) {
            echo '<div class="feed_reload hide">1</div>';
        }
    }
}

if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php
}
?>
</div>
<div class="boxContentWrap btnUnanswered"<?php if($type != 'btnUnanswered') echo ' style="display: none;"';?>>
<?php
if ($type == 'btnUnanswered'){
    if (empty($questions)) {
        echo $this->Element('Frontend/questionlist/notfound');
    } else {
        echo '<ul>';
        foreach ($questions as $questionData) {
            echo $this->Element('Frontend/question/list_part', array('questionData' => $questionData));
        }
        echo '</ul>';
        if(count($questions) >= LIMIT_QUESTION) {
            echo '<div class="feed_reload hide">1</div>';
        }
    }
}

if ($questions && ($this->request->params['paging']['Question']['count'] > LIMIT_QUESTION)) {
?>
  <div class="boxShowMore">
    <p class="js-SeeMore mod-btn mod-btnSeeMore mod-icn l-btnLogin-center"><img class="mod-preloadimg" src="/img/common/loading.gif"></p>
  </div>
<?php
}
?>
</div>




<?php
if (isset($User)) {
  $login_class="";
  $login_url=Router::url(array('controller' => 'questions', 'action' => 'input'));
} else {
  $login_class="btnModalLogin";
  $login_url="#";
}
?>

<?php echo $this->Element('Frontend/questionlist/bottom', array('login_class' => $login_class, 'login_url' => $login_url));?>

</div>

<div id="sideContainer" class="l-sideContent">

<?php //Myタグ
//if(isset($User)) {
    echo $this->Element('Frontend/sidebar/my_tags', array(
        'myTags' => $myTags,
    ));
//}
?>

<?php //人気のタグ
echo $this->Element('Frontend/sidebar/popular_tags', array(
        'popTags' => $popTags,
        'myTags' => $myTags,
    ));
?>

<?php //ピックアップユーザー
echo $this->Element('Frontend/sidebar/active_users', array(
    'userList' => $users,
    'myFollowList' => $follows,
));
?>
<?php /* if (isset($User)){ ?>
<section class="boxLatest boxAcquaintance clearfix">
  <p class="ttlSub ttlSub_bkgBk ttlAcquaintance">知り合いを探す</p>
  <ul id="followScroll" class="boxUserStatList sidesocial">

  </ul>
</section>
<?php } */ ?>
<div class="l-sideBox-twitter">
    <a class="twitter-timeline"  href="https://twitter.com/leveragesvn" data-widget-id="504507867643117568">Tweet của @leveragesvn</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>
</div>
</div>

<?php $this->start('body_id'); ?>pageID_top<?php $this->end();?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>

<?php
$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
    'frontend/feed',
), array('inline' => false, 'block' => 'scriptBottom'));
?>

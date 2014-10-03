<?php $this->Html->css(array('sp/index'),null,array('inline' => false)); ?>
<?php $this->Html->script(array('sp/common','sp/feed'),array('inline' => false)); ?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<?php $this->end();?>
<?php $this->start('body_id'); ?>pageID_top<?php $this->end();?>
<?php if (!isset($User)): ?>
<section class="bkgLoginTitle clearfix">
    <div class="content">
        <form action="https://teratail.com/login/request" class="form-horizontal margin-none" id="UserSignupForm" method="post" accept-charset="utf-8">
            <input type="hidden" name="_method" value="POST"/>
            <div class="boxForm">
                <p class="ttlCopy">Dành cho tất cả engineer giải quyết vấn đề <br>Q&amp;A Trang Web<br>
                  <?php echo $this->Html->image('sp/common/login/imglogoLogin_white.png', array('alt' => "teratail", 'width' => '120', 'height' => '55'));?>
                </p>
                <h2 class="ttlMain ttlMain--request">Đăng nhập bằng tài khoản SNS/Đăng ký mới</h2>
                <div class="boxForm__snsBlockArea">
                  <ul class="snsBlock clearfix">
                  <?php foreach(array('Facebook' => 'facebook', 'Twitter' => 'twitter', 'Google' => 'google','Github' => 'GitHub', 'Hatena' => 'Hatena') as $brand => $name){?>
                    <li class="btnLogin snsBlock__list">
                      <?php
                      $brand_lowercase = strtolower($brand);
                      echo $this->Html->image('sp/common/login/icnLogin'.$brand.'.png', array(
                          "alt" =>'Đăng nhập bằng ' . $brand,
                          'url' => array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                          'class' => '',
                          'escape' => false,
                          'width' => '50',
                          'height' => '50',
                      ));
                      ?>
                    </li>
                  <?php } ?>
                  </ul>
                    <p>Ngoài ra</p>
                </div>
                <div><a href="/login/input" class="submitBtn submitBtn-large">Đăng ký mới bằng tài khoản email</a></div>
                <ul class="txtLink-group">
                    <li>
                        <a href="/login/request" class="icoArrow icoImg-white txtLink-white">Đăng nhập Tại đây</a>
                    </li>
                    <li>
                        <a href="/about" class="icoArrow icoImg-white txtLink-white">Tìm hiểu chi tiết hơn về teratail</a>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</section>
<?php endif; ?>
<nav class="navTabs-up">
  <ul id="tab" class="navTabs navTabs-short boxSelectTab">
    <li class="btnAttention on">Đáng chú ý</li>
    <li class="btnNew">Mới đăng</li>
    <li class="btnUnresolved">Chưa giải quyết</li>
    <li class="btnResolved">Đã giải quyết</li>
  </ul>
</nav>
<div class="boxContentWrap btnAttention">
<?php
  if (empty($questions)) {
    echo $this->Element('Mobile/question/list_notfound');
  } else {
    echo '<ul>';
    foreach ($questions as $questionData) {
      echo $this->Element('Mobile/question/list_part', array('questionData' => $questionData));
    }
    echo '</ul>';
  }
  if(count($questions) >= LIMIT_QUESTION) {
    echo '<div class="feed_reload hide">1</div>';
  }
?>
  <div class="boxShowMore">
    <button class="btnShowMore btn">...Xem thêm</button>
  </div>
</div>
<div class="boxContentWrap btnNew" style="display: none;">
  <div class="boxShowMore loading">
    <button class="btnShowMore btn">...Xem thêm</button>
  </div>
</div>
<div class="boxContentWrap btnUnresolved" style="display: none;">
  <div class="boxShowMore loading">
    <button class="btnShowMore btn">...Xem thêm</button>
  </div>
</div>
<div class="boxContentWrap btnResolved" style="display: none;">
  <div class="boxShowMore loading">
    <button class="btnShowMore btn">...Xem thêm</button>
  </div>
</div>

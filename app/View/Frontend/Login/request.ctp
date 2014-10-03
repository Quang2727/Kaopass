<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>

<div id="content">
  <div id="boxLoginRequest">
    <div class="boxInner clearFix">
      <div class="floatL boxLeft">
        <?php foreach(array('Facebook' => 'facebook', 'Twitter' => 'twitter', 'Google' => 'google','Github' => 'GitHub'/*, 'Hatena' => 'Hatena'*/) as $brand => $name){?>
        <p class="btnLogin">
            <?php
            $brand_lowercase = strtolower($brand);
            echo $this->Html->link(
                '<img src="/img/login/btnLogin' . $brand.'.png" alt="Đăng nhập bằng '.$brand.'" width="260" height="41">',
                array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                array('class' => '', 'escape' => false)
            );
            ?>
        </p>
        <?php } ?>
      </div>
      <div class="floatR boxRight l-boxLogin">
        <h2>Đăng nhập bằng teratail</h2>
        <?php echo $this->Session->flash();?>
        <?php echo $this->Element('Frontend/forms/login'); ?>
      </div>
    </div>
  </div>
</div>

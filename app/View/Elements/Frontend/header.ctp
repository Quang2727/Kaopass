<div class="boxInner clearfix"> 
  <p id="ttlHeader">
    <a href="/">
<?php
echo $this->Html->image('common/ttlHeader.png',
    array(
        "alt" => "teratail",
        "width" => "132",
        "height" => "40"
    )
);
?>
    </a>
  </p>
<?php
if (empty($this->request->params['ref'])):
  echo $this->Element('Frontend/topmenu/search');
  if (isset($User)) {
    $login_class="";
    $login_url=Router::url(array('controller' => 'questions', 'action' => 'input'));
  } else {
    $login_class="btnModalLogin";
    $login_url="#";
  }
?>
  <div class="btnAsk <?php echo $login_class;?>">
      <a href="<?php echo $login_url;?>" class="mod-btn mod-btnAsk mod-icn">Đặt câu hỏi</a>
  </div>

<?php
    if (isset($User)) {
        echo $this->Element('Frontend/topmenu/member');     
    } else {
        echo $this->Element('Frontend/topmenu/guest');
    }
?>
<?php endif; ?>    
</div>


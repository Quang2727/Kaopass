<!-- バッジ表示領域 -->
<section class="boxBadgeGet">
  <div class="boxBadgeInner clearFix">
    <div class="boxBadgeImage floatL">
      <?php
        $badge_path = !empty($message['image']) ? preg_replace('/\/imgBadge_/i', '/mini_', $message['image']) : 'badges/icnBadge_medal.jpg';
        echo $this->Html->image( $badge_path ,array('alt'=>$message['name'], 'width'=>'100', 'height'=>"100"));
      ?>
    </div>
    <div class="txtBadgeDescription floatL">
      <button class="btnClose"><img src="/img/common/btnClose.png" alt="Đóng" width="46" height="46"></button>
      <p class="txtBadge">Bạn đã đạt được huy hiệu!</p>
      <p class="boxTtlBadge">“<span class="ttlBadge"><?php echo $message['name']; ?></span>”</p>
      <p><?php echo $message['comment']; ?></p>
      <p><a href="/users/<?php echo h($User["display_name"]);?>/badge"> Xem danh sách huy hiệu</a></p>
    </div>
  </div>
</section>
<!-- /バッジ表示領域 -->

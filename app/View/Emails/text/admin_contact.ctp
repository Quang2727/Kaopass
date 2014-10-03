Hệ thống vừa nhận được câu hỏi sau, vui lòng kiểm tra.

Tiêu đề：<?php echo $data['Contact']['title']. "\n"; ?>
-----------------------------------
Ngày gửi         ：  <?php echo date(DATETIME_FORMAT). "\n"; ?>
Địa chỉ mail     ：  <?php echo$data['Contact']['mail_address']. "\n"; ?>
Chủ đề liên hệ   ：  <?php echo$data['Contact']['subject']. "\n"; ?>
Tiêu đề          ：  <?php echo $data['Contact']['title']. "\n"; ?>
Nội dung         ：
<?php echo$data['Contact']['content']. "\n"; ?>

------------------------------------
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/

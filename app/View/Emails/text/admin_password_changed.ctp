Mật khẩu của <?php echo h($display_name); ?> đã được thay đổi, vui lòng kiểm tra.
-----------------------------------
Ngày đổi      :　<?php echo date(DATETIME_FORMAT). "\n"; ?>
Tên user      :　<?php echo h($display_name). "\n"; ?>
Địa chỉ mail  :　<?php echo h($mail_address). "\n"; ?>
-----------------------------------
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/

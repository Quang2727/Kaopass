Đã đăng ký admin <?php echo h($display_name); ?> thành công, vui lòng kiểm tra.
-----------------------------------
Ngày tạo             ：　<?php echo h($created). "\n"; ?>
Tên hiển thị         ：　<?php echo h($display_name). "\n"; ?>
Địa chỉ mail         ：　<?php echo h($mail_address). "\n"; ?>
Bộ phận trực thuộc   ：　<?php echo h($sip). "\n"; ?>
Người phụ trách      ：　<?php echo h($referer). "\n"; ?>
Nhật ký truy cập     ：　
<?php echo h($first_access_history). "\n"; ?>
<?php $history = unserialize($recently_access_history); 
foreach($history as $value) {
    echo h($value). "\n";
}

?>
-----------------------------------
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/

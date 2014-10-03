Chào anh/chị <?php echo h($to_display_name); ?>

Anh/chị nhận được yêu cầu trả lời từ thành viên <?php echo h($from_display_name); ?> cho câu hỏi dưới đây
---------------------------------
Tiêu đề：「<?php echo h($Question['title']); ?>」
Ngày đăng：<?php echo h(date('Y-m-d H:i', strtotime($Question['created'])))."\n"; ?>
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>

Nội dung：
<?php echo h($text)."\n"; ?>

▼Chi tiết▼
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>
---------------------------------


Chào anh/chị <?php echo h($to_display_name); ?>
Đây là hệ thống teratail。

Thành viên <?php echo h($from_display_name); ?> đã có bình luận về câu hỏi của anh/chị

Tiêu đề: 「<?php echo h($Question['title']); ?>」
Ngày đăng: <?php echo h(date('Y-m-d H:i', strtotime($Question['created'])))."\n"; ?>
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>

Nội dung:
<?php echo h($text)."\n"; ?>

▼Chi tiết▼

<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>


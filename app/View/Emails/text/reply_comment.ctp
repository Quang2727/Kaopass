▼Bình luận▼
Tên thành viên：<?php echo h($from_display_name); ?>
Ngày đăng：<?php echo h($reply_datetime)."\n"; ?>
<?php echo h($text)."\n"; ?>

▼Câu trả lời được bình luận▼
Tên thành viên：<?php echo h($to_display_name); ?>
Ngày đăng：<?php echo h(date('Y-m-d H:i', strtotime($Reply['created'])))."\n"; ?>
<?php echo h($reply_text)."\n"; ?>

<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Reply['question_id'])."\n"; ?>

Trường hợp bạn muốn tạm dừng nhận mail từ hệ thống chúng tôi, hãy ấn vào URL bên dưới.
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/mail/setting?mailid=<?php echo $mail_id ?>


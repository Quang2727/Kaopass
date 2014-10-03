▼Câu hỏi đã theo dõi▼
Tiêu đề：<?php echo h($Question['title'])."\n"; ?>
Tên thành viên：<?php echo h($Question['User']['display_name']); ?>
Ngày đăng：<?php echo h(date('Y-m-d H:i', strtotime($Question['created'])))."\n"; ?>
<?php echo h($Question['body'])."\n"; ?>

<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>

▼Trả lời▼
Tên thành viên：<?php echo h($User['display_name']); ?>
Ngày đăng：<?php echo h(date('Y-m-d H:i', strtotime($Reply['created'])))."\n"; ?>
<?php echo h($Reply['body'])."\n"; ?>

<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($Question['id'])."\n"; ?>

Trường hợp bạn muốn tạm dừng nhận mail từ hệ thống chúng tôi, hãy ấn vào URL bên dưới.
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/mail/setting?mailid=<?php echo $mail_id ?>

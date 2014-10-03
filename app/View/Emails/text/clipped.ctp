▼Câu hỏi được thành viên khác theo dõi▼
<?php foreach($ClippedQuestion as $value) { ?>
Tiêu đề：<?php echo h($value['Question']['title'])."\n"; ?>
Ngày đăng：<?php echo h(date('Y-m-d H:i', strtotime($value['Question']['created'])))."\n"; ?>
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/questions/<?php echo h($value['Question']['id'])."\n"; ?>
Số người theo dõi：<?php echo h($value['Question']['clipped_count'])."\n"; ?>

<?php } ?>

Trường hợp bạn muốn tạm dừng nhận mail từ hệ thống chúng tôi, hãy ấn vào URL bên dưới.
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/mail/setting?mailid=<?php echo $mail_id ?>

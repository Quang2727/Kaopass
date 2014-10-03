Đã có câu trả lời cho câu hỏi số <?php echo h($Question['id']); ?>, vui lòng kiểm tra.
Tiêu đề： 「<?php echo h($Question['title']); ?>」
-----------------------------------
Thành viên đặt câu hỏi   ： <?php echo h($display_name)."\n"; ?>
Ngày đăng                ：　<?php echo h(date('Y-m-d H:i', strtotime($Question['created'])))."\n"; ?>
Thành viên trả lời       ：　<?php echo h($User['display_name'])."\n"; ?>
Ngày đăng                ：　<?php echo h(date('Y-m-d H:i', strtotime($Reply['created'])))."\n"; ?>
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/
-----------------------------------

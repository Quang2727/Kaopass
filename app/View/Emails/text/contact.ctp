Chào anh/chị <?php echo$data['Contact']['mail_address']. "\n"; ?>
---------------------------------------------------------------------
Đây là một email được gửi tự động
Vui lòng không trả lời email này
Nếu có thắc mắc xin mời liên hệ 「info-teratail-vn@leverages.jp」
---------------------------------------------------------------------

Cám ơn bạn đã gửi câu hỏi cho hệ thống teratail
Chúng tôi đã tiếp nhận câu hỏi với các thông tin như sau:
----------------------
Ngày gửi        ：  <?php echo date(DATETIME_FORMAT). "\n"; ?>
Địa chỉ mail    ：  <?php echo$data['Contact']['mail_address']. "\n"; ?>
Chủ đề liên hệ  ：  <?php echo$data['Contact']['subject']. "\n"; ?>
Tiêu đề         ：  <?php echo $data['Contact']['title']. "\n"; ?>
Nội dung        ：
<?php echo$data['Contact']['content']. "\n"; ?>
----------------------

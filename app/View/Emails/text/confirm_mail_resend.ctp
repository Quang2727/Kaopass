Chào anh/chị <?php echo h($display_name); ?>

Hãy hoàn thành xác nhận mail bằng cách nhấn vào URL dưới đây
<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>/users/social/signup/mail/<?php echo $unique_key;?>

Mail đăng ký này chỉ có hiệu lực trong vòng 30 phút.
Trong trường hợp mail đã quá 30 phút, xin vui lòng hãy thực hiện lại việc đăng ký tại hệ thống.

・Xác nhận mail để sử dụng tất cả các chức năng của hệ thống
・Toàn bộ những thông báo của hệ thống sẽ được gửi đến mail này

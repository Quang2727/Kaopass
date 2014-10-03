<p>Chào bạn <?php echo $user['User']['display_name']; ?>,</p>
<p>Bạn vừa mới yêu cầu mật khẩu mới để đăng nhập vào hệ thống của chúng tôi.</p>
<p>
    Dưới đây là thông tin đăng nhập của bạn:<br />
    <br />
    Địa chỉ mail: <?php echo $user['User']['mail_address']; ?><br />
    Mật khẩu: <?php echo $new_password; ?><br />
</p>
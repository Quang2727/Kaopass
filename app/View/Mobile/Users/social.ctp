<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css(array('sp/login/input','sp/users/setting.css'), null, array('inline' => false));

$this->assign('body_id', 'pageID_social');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>


<!-------------------- Main start ---------------------->
<div class="content">
	<div class="boxForm">
    <h2 class="ttlMain">Thiết lập liên kết SNS</h2>
    <nav class="navTabs-up">
        <ul class="navTabs navTabs-full">
            <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'profile')) ?>" class="sidebar">Thay đổi thông tin</a></li>
            <li><a href="/users/setting/password" class="sidebar">Thay đổi mật khẩu</a></li>
        </ul>
    </nav>
    <section class="boxPost">
        <h3 class="ttlRule">Trường hợp xóa tất cả các liên kết SNS</h3>
        <p class="txtDetail">Nếu xóa tất cả các liên kết SNS, bạn cần thiết lập lại những thông tin bên dưới<br>
            Thay đổi thông tin &gt; Địa chỉ Email<br>
            Thay đổi mật khẩu &gt; Mật khẩu
        </p>
            <dl class="social_form">
        <?php foreach(array('Facebook' => 'Facebook', 'Twitter' => 'Twitter', 'Google OpenID' => 'Google','Github' => 'GitHub', 'Hatena' => 'Hatena') as $brand => $name){
                $brand_lowercase = strtolower($brand);
            ?>
            <dt class="socialLabel"><?php echo $name; ?></dt>
            <dd class="socialBtn">
                
            <?php
                if(isset($sns_info[$brand_lowercase])) {
                    $string = 'Xóa';
                    $url = '/login/cancelsocial/'.$brand_lowercase;
                    echo "<p class='social_cancel'>";
                    if(empty($user["password"]) && count($sns_info) <= 1) {
                        echo $string;
                    } else {
                        echo $this->Html->link(
                            $string,
                            $url
                        );                    
                    }
                    echo "</p>";
                } else {
                    $string = 'Liên kết';
                    $url = '/login/addsocial/'.$brand_lowercase;                    
                    echo "<p class='social_add'>";                    
                    echo $this->Html->link(
                        $string,
                        $url
                    );                    
                    echo "</p>";                    
                }
            ?>
             </dd>
        <?php } ?>                

            </dl>
	</section>
</div>
</div>
<!-------------------- Main end ---------------------->

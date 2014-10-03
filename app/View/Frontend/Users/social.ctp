<?php
$this->assign('keywords', '');
$this->assign('description', '');

$this->Html->css('users/setting', null, array('inline' => false));

$this->assign('body_id', 'pageID_social');
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Thiết lập liên kết SNS</li>
<?php $this->end();?>

<!-------------------- Main start ---------------------->
<div id="mainContainer">
    <h1 class="ttlMain">Thiết lập liên kết SNS</h1>
    <section class="boxPost">
            <dl class="social_form">
        <?php foreach(array('Facebook' => 'Facebook', 'Twitter' => 'Twitter', 'Google OpenID' => 'Google','Github' => 'GitHub') as $brand => $name){
                $brand_lowercase = strtolower($brand);
            ?>
            <dt><?php echo $name; ?></dt>
            <dd>
                
            <?php
                if(isset($sns_info[$brand_lowercase])) {
                    $string = 'Bỏ liên kết';
                    $url = '/login/cancelsocial/'.$brand_lowercase;
                    echo "<p class='social_cancel'>";
                    if(empty($user["password"]) && count($sns_info) <= 1) {
                        echo $string;
                    } else {
                        echo $this->Html->link(
                            $string,
                            $url,
                            array(
                                'class' => 'mod-btn mod-btnSubmit mod-btnSocial is-cancel',
                            )
                        );
                    }
                    echo "</p>";
                } else {
                    $string = 'Liên kết';
                    $url = '/login/addsocial/'.$brand_lowercase;
                    echo "<p class='social_add'>";
                    echo $this->Html->link(
                        $string,
                        $url,
                        array(
                            'class' => 'mod-btn mod-btnSubmit mod-btnSocial',
                        )
                    );                    
                    echo "</p>";                    
                }
            ?>
             </dd>
        <?php } ?>

            </dl>
    </section>
</div>
<!-------------------- Main end ---------------------->


<!-------------------- Side start ---------------------->
    <div id="sideContainer" class="l-sideContent"> 
        <!---- ▼設定ボタン start ---->
        <nav>
            <ul class="boxSettingSelect">
                <li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'profile')) ?>" class="sidebar">Thay đổi thông tin</a></li>
                <?php if ($user['mail_confirm_flag']): ?><li><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'password')) ?>" class="sidebar">Thay đổi mật khẩu</a></li><?php endif; ?>
                <li class="sidebar_active">Thiết lập liên kết SNS</li>
            </ul>
        </nav>
        <section class="boxPostRule">
            <h2 class="ttlSub">Các lưu ý khi thiết lập</h2>
            <h3 class="ttlRule">Trường hợp bỏ liên kết tất cả SNS</h3>
            <p>
                Trường hợp bỏ tất cả liên kết đến SNS, bạn cần phải thiết lập thêm<br>
                - Thay đổi thông tin &gt; địa chỉ mail<br>
                - Thay đổi mật khẩu &gt; mật khẩu
            </p>
        </section>
        <!---- 設定ボタン end ------>
    </div>
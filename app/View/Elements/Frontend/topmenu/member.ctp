<div id="boxUsernav">
    <div id="icnUser" class="l-userMenu">
        <figure class="l-userMenu-head">
<?php $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$User['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$User['photo']; ?>
            <?php echo $this->Html->image(
                $img_src,
                array(
                    'width' => '20',
                    'height' => '20',
                    'alt' => h($User['display_name']),
                    'class' => "mod-thumb",
                ));
            ?><figcaption><?php echo h($User['display_name']);?></figcaption>
        </figure>
        <span id="user-id" class="hide"><?php echo $User['id']; ?></span>
        <div id="boxUserMenu" class="l-userMenu-body">
            <ul>
                <li class="bkgMypage"><?php echo $this->Html->link('MyPage', array('controller' => 'users', 'action' => 'info', 'username' => $User['display_name'])); ?></li>
                <li class="bkgKininaru"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'info', 'username' => $User['display_name'], 'ClipQuestion')); ?>">Câu hỏi đang theo dõi<span><?php echo isset($user_count[11])?$user_count[11]:0; ?></span></a></li>
                <li class="bkgQuestion"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'info', 'username' => $User['display_name'], 'Question')); ?>">Câu hỏi của bạn<span><?php echo isset($user_count[10])?$user_count[10]:0; ?></span></a></li>
                <li class="bkgAnswer"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'info', 'username' => $User['display_name'], 'Reply')); ?>">Câu hỏi bạn trả lời<span><?php echo isset($user_count[4])?$user_count[4]:0; ?></span></a></li>
                <li class="bkgProfile"><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'profile')) ?>">Thay đổi thông tin</a></li>
                <li class="bkgLogout"><?php echo $this->Html->link(
                    'Đăng xuất', array('controller' => 'login', 'action' => 'logout'));
                ?></li>
            </ul>
        </div>
    </div>
<?php
if (empty($this->request->params['ref']))
    echo $this->element("Frontend/topmenu/notification");
?>
</div>

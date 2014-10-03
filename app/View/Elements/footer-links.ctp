<ul class="boxSiteNav clearfix">
    <li><?php echo $this->Html->link('Danh sách tag', 
        array('controller' => 'Tags', 'action' => 'index')
    ); ?></li>
    <li><?php echo $this->Html->link('Tìm kiếm thành viên', 
        array('controller' => 'users', 'action' => 'index')
    ); ?></li>
    <li><?php echo $this->Html->link('Teratail là gì?', array(
        'controller' => 'pages',
        'action' => 'display',
        'about'
    )); ?></li>
</ul>
<ul class="boxSiteNav clearfix">
    <li><?php echo $this->Html->link('Công ty vận hành', 
        'http://leverages.vn/',
        array('target' => '_blank')
    ); ?></li>
    <li><?php echo $this->Html->link('Điều khoản sử dụng', array(
        'controller' => 'pages',
        'action' => 'display',
        'code'
    )); ?></li>
    <li><?php echo $this->Html->link('Chính sách bảo mật thông tin cá nhân', array(
        'controller' => 'pages',
        'action' => 'display',
        'privacy'
    )); ?></li>
    <!--<li>
         <?php //echo $this->Html->link('Phương châm hoạt động', 
        // 'http://leverages.jp/privacypolicy/',
        // array('target' => '_blank')
        // ); ?></li>
    -->
</ul>

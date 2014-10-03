<div class="span8 offset2">
    <div class="row-fluid">
        <div class="widget widget-4 span7">
            <div class="widget-head">
                <h4 class="heading">Đăng nhập</h4>
            </div>
            <div class="widget-body">
                <?php echo $this->Session->flash();?>
                <?php echo $this->Element('Frontend/forms/login'); ?>
            </div>
        </div>

        <div class="widget widget-4 span4 offset1">
            <div class="widget-head">
                <h4 class="heading">Mạng xã hội</h4>
            </div>
            <div class="widget-body">
                <ul class="nav nav-list">
                <?php foreach(array('Facebook', 'Twitter', 'GitHub', 'Google'/*, 'Hatena'*/) as $brand){?>
                    <li>
                        <?php
                        $brand_lowercase = strtolower($brand);
                        echo $this->Html->link(
                            '<b class="icon-'.$brand_lowercase.'"></b> ' . $brand,
                            array('controller' => 'login', 'action' => 'social', $brand_lowercase),
                            array('class' => '', 'escape' => false)
                        );
                        ?>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
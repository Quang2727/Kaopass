<div class="nav-filter clearfix">
    <ul class="nav nav-pills">
        <?php
        $sortTypes = Configure::read('user.filter');
        $action = strtolower($this->request->params['action']);
        
        $controller = strtolower($this->request->params['controller']);
        foreach ($sortTypes as $type => $label) {
            $filter_url = array(
                'controller' => 'users',
                'action' => 'index',
                $type,
                $this->Session->read("sort_order")
            );
            $class_name = ($type == $filter) ? 'active' : '';
            echo '<li class="' . $class_name . '">' . $this->Html->link(
                    $label, $filter_url, array('title' => $label)
            ) . '</li>';
        }
        ?>
    </ul>
</div><!-- /.nav-filter -->
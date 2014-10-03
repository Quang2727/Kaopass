<?php
if (empty($questions) && $paramarter["sort"] == SORT_DATE) {
    return;
}
if (!empty($emptyFilter))
    return;

$this->Html->script(array(
    'plugins/bootstrap-tabdrop',
), array('inline' => false, 'block' => 'scriptBottom'));
?>
<nav>
<ul id="tab" class="boxSelectTab clearfix">
        <?php
        $sortTypes = Configure::read('tag.sort');
        $action = strtolower($this->request->params['action']);
        $controller = strtolower($this->request->params['controller']);

        foreach ($sortTypes as $type => $label) {
            $add_class = 'btnNew';
            $image_src = 'common/btnSelectTab.png';
            if (!empty($action) || (empty($action) && ($type == SORT_VIEW || $type == SORT_RATING))) {
                if ($controller == 'tags') {
                    $query = "";
                    if($type !== 'date') {
                        $query = '?order='.$type;
                    }
                    $filter_url = '/tags/' . $key_tag . '/'.$query;

                    if($type == 'view') {
                        $add_class = 'btnAttention';
                    } else if($type == 'rating') {
                        $add_class = 'btnTopRated';
                    } else if($type == 'done') {
                        $add_class = 'btnResolved';
                    } else if($type == 'not_done') {
                        $add_class = 'btnUnresolved';
                    }
                    parse_str($_SERVER["QUERY_STRING"],$query_to_array);
                    $class_name = '';
                    if(isset($query_to_array["order"]) && $query_to_array["order"] == $type) {
                        $class_name = ' on';
                    } else if(!isset($query_to_array["order"]) && $add_class == 'btnNew'){
                        $class_name = ' on';
                    }
                } else if ($controller == 'search') {
                    $query_list = array('q' => $query->query);
                    if($type !== 'date') {
                        $query_list['order'] = $type;
                    }
                    $filter_url = array(
                        'controller' => 'questions',
                        'action' => 'search',
                        '?' => $query_list
                    );
                    if($type == 'view') {
                        $add_class = 'btnAttention';
                    } else if($type == 'rating') {
                        $add_class = 'btnTopRated';
                    } else if($type == 'done') {
                        $add_class = 'btnResolved';
                    } else if($type == 'not_done') {
                        $add_class = 'btnUnresolved';
                    }
                    parse_str($_SERVER["QUERY_STRING"],$query_to_array);
                    $class_name = '';
                    if(isset($query_to_array["order"]) && $query_to_array["order"] == $type) {
                        $class_name = ' on';
                    } else if((!isset($query_to_array["order"]) || empty($query_to_array["order"])) && $add_class == 'btnNew'){
                        $class_name = ' on';
                    }
                } else {
                    $filter_url = array(
                        'controller' => 'questions',
                        'action' => $action,
                        $type,
                        isset($paramarter['id']) ? $paramarter['id'] : '',
                        isset($paramarter['tag_id']) ? $paramarter['tag_id'] : '',
                        isset($paramarter['type']) ? $paramarter['type'] : ''
                    );
                    $class_name = ' on';
                    if($type == 'view') {
                        $add_class = 'btnAttention';
                    } else if($type == 'rating') {
                        $add_class = 'btnTopRated';
                    } else if($type == 'done') {
                        $add_class = 'btnResolved';
                    } else if($type == 'not_done') {
                        $add_class = 'btnUnresolved';
                    }
                    $uri_split = explode("/",parse_url($_SERVER['REQUEST_URI'])['path']);
                    $class_name = '';
                    if(isset($uri_split['3']) && $uri_split['3'] == $type) {
                        $class_name = ' on';
                    } else if((!isset($uri_split['3']) || empty($uri_split['3'])) && $add_class == 'btnNew'){
                        $class_name = ' on';
                    }
                }
                //$class_name = ($type == $paramarter["sort"]) ? 'active' : '';
                if (!empty($myQuestion)) {
                    if ($type == SORT_VIEW || $type == SORT_RATING)
                        continue;
                }
                echo '<li class="'. $add_class . $class_name . '"><p><span>' . $this->Html->link(
                        $this->Html->image($image_src, array('alt' => $label)), $filter_url, array('escape' => false)
                ) . '</span></p></li>';
            }
        }
        ?>
</ul>
</nav>

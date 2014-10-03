<?php $this->Html->css(array('/css/users/user_tooltipster.css'), NULL, array('inline' => false)); ?>
<?php $this->Html->script(array('/js/jquery.tooltipster.js'), array('inline' => false)); ?>
<?php $this->Html->script('/js/frontend/users/search.js', array('inline' => false)); ?>
<?php
//default paging object
if (!isset($paging)) {
    $paging = (isset($model_name)) ? $this->request->params['paging'][$model_name] : $this->request->params['paging'];
}

//default modulus
if (!isset($modulus)) {
    $modulus = 10;
}
?>
<div class="boxPager clearfix">
    <p>
        <?php
        $start_index = ($paging['page'] - 1) * $paging['limit'] + 1;
        $end_index = $start_index + $paging['current'] - 1;
        ?>
        <?php echo $start_index ?> - <?php echo $end_index ?> trong  <?php echo $paging['count']; ?> kết quả
    </p>

    <?php if ($paging['pageCount'] > 1): ?>
        <ul id="userPager">
            <?php
            $controller = strtolower($this->request->params['controller']);
            $action = strtolower($this->request->params['action']);
            if ($controller == 'tags') {
                $url = array('controller' => 'tags', 'action' => 'view',
                    isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : "",
                    isset($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : ""
                );
                $query_list = array();
                if($paramarter["sort"] !== 'date') {
                    $query_list['order'] = $paramarter["sort"];
                }
                $this->Paginator->options = array(
                    'url' => am(
                            $url,                            
                            array('?' => $query_list)
                    )
                );
            } else if ($controller == 'users' && $action == "info") {
                $url = array('controller' => 'users', 'action' => 'info',
                    isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : "",
                    isset($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : "",
                    isset($this->request->params['pass'][2]) ? $this->request->params['pass'][2] : ""
                );
                $this->Paginator->options = array(
                    'url' => $url
                );
            } else if ($controller == 'search' && $action == "question") {
                $url = array('controller' => 'questions', 'action' => 'search',
                    isset($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : "",
                );
                $query_list = array('q' => $query->query);
                if($paramarter["sort"] !== 'date') {
                    $query_list['order'] = $paramarter["sort"];
                }
                $this->Paginator->options = array(
                    'url' => am(
                            $url,
                            array('?' => $query_list)
                    )
                );
            }
            $page = $paging['page'];

//            if ($page > 1)
//                echo $this->Paginator->prev('<', array(
//                    'tag' => 'li',
//                    'class' => 'prev',
//                        ), $this->Paginator->link('<', array()), array(
//                    'tag' => 'li',
//                    'escape' => false,
//                    'class' => 'prev disabled',
//                ));

            //numbers
            $pageCount = $paging['pageCount'];

            if ($modulus > $pageCount) {
                $modulus = $pageCount;
            }

            //$start = $page - intval($modulus / 2);
            $start = $page - 3;

            if ($start < 1) {
                $start = 1;
            }

            $end = $start + $modulus;
            if ($end > $pageCount) {
                $end = $pageCount + 1;
                $start = $end - $modulus;
            }

            for ($i = $start; $i < $end; $i++) {
                $this->request->params['named']['page'] = $i;
                $url = $this->request->params['named'];
                if (isset($this->request->params['ref'])) {
                    $url['ref'] = $this->request->params['ref'];
                }
                $class = null;
                if ($i == $page) {
                    echo '<li>'.$i.'</li>';
                } else {
                    $url_link = $this->Html->tag('li', $this->Paginator->link($i, $url));
                    if($i == 1) {
                        $url_link = preg_replace('/(page:1)/', '', $url_link);
                    }
                    echo preg_replace('/(page:)/', 'p', 
                            preg_replace('/\/view\//', '/', 
                            preg_replace('/(name:)/', '', $url_link
                    )));
                }
            }

//            if ($page < $pageCount)
//            //next & last
//                echo $this->Paginator->next('>', array(
//                    'tag' => 'li',
//                    'class' => 'next',
//                        ), $this->Paginator->link('>', array()), array(
//                    'tag' => 'li',
//                    'escape' => false,
//                    'class' => 'next disabled',
//                ));

            ?>
        </ul>
    <?php endif; ?>
</div>

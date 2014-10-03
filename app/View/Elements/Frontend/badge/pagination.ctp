<?php
//default modulus
if (!isset($modulus)) {
    $modulus = 10;
}
?>

<div class="boxPager clearfix">
    <p>
        <?php
        $start_index = ($pager['page'] - 1) * $pager['limit'] + 1;
        $end_index = $start_index + $pager['current'] - 1;
        ?>
        <?php echo $start_index ?> - <?php echo $end_index ?> trong <?php echo $pager['count']; ?> kết quả
    </p>

    <?php if ($pager['pageCount'] > 1): ?>
        <ul>
            <?php
            $controller = strtolower($this->request->params['controller']);
            $action = strtolower($this->request->params['action']);
            $url = array('controller' => 'badges', 'action' => 'index');
            $this->Paginator->options = array(
                'url' => $url
            );
            $page = $pager['page'];

            //numbers
            $pageCount = $pager['pageCount'];

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
                $url = array_merge($this->request->params['named'], array('page' => $i));
                if (isset($this->request->params['ref'])) {
                    $url['ref'] = $this->request->params['ref'];
                }
                $class = null;
                if ($i == $page) {
                    echo '<li>'.$i.'</li>';
                } else {
                    echo $this->Html->tag('li', $this->Paginator->link($i, $url));
                }
            }

            ?>
        </ul>
    <?php endif; ?>
</div>   


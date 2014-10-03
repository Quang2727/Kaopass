<?php 
if (!isset($modulus)) {
    $modulus = 10;
}
$model_name = isset($model_name) ? $model_name : 'User';

if (!isset($paging)) {
    $paging = (isset($this->request->params['paging'][$model_name])) ? $this->request->params['paging'][$model_name] : $this->request->params['paging'];
}
?>
<?php
$page = $paging['page'];
$pageCount = $paging['pageCount'];

if ($modulus > $pageCount) {
    $modulus = $pageCount;
}

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
    //                $this->request->params['named']['page'] = '#';
    $url = $this->request->params['named'];
    if (isset($this->request->params['ref'])) {
        $url['ref'] = $this->request->params['ref'];
    }
    $class = null;
    if ($i == $page) {
        echo '<li class="now">'.$i.'</li>';
    } else {
        echo $this->Html->tag('li', $this->Paginator->link($i, $url, array('onclick' => 'scrollTo(0,0)')));
    }
}

?>

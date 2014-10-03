<?php
//default paging object
if (!isset($paging)) {
    if(isset($model_name)) {
        $paging = (isset($this->request->params['paging'][$model_name])) ? $this->request->params['paging'][$model_name] : $this->request->params['paging'];
    } else {
        $paging = (isset($this->request->params['paging']['User'])) ? $this->request->params['paging']['User'] : $this->request->params['paging'];
    }
}
$start_index = ($paging['page'] - 1) * $paging['limit'] + 1;
$end_index = $start_index + $paging['current'] - 1;
if($paging['count'] > 0) {
?>
<?php echo $start_index ?> - <?php echo $end_index ?> trong <?php echo $paging['count']; ?> kết quả
<?php } ?>

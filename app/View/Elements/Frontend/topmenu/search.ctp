<?php
if (isset($this->request->params['ref']))
    return;
if (!isset($search_type)) {
    $search_type = 'question';
}
?>

<div id="boxSearch">
<?php
echo $this->Form->create(null, array(
    'url' => '/questions/search',
    'type' => 'get',
    'id' => 'boxSearch-form',
    'class' => 'form-horizontal margin-none'
));
?>
    <div class="boxLabelInput">
        <input class="txtSearch" id="boxSearch-query" type="text" name="q" autocomplete="off"  size="15" value="<?php echo !empty($query->query) ? h(@$query->query) : ''; ?>" placeholder="Tìm kiếm theo từ khóa" />        
    </div>
    <p class="btnSearch">
        <a href="#" id="boxSearch-submit" class="a_submit mod-icn">Bắt đầu tìm</a>
    </p>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Html->script('frontend/topsearch', array('async' => 'async')); ?>
    <ul id="top-suggest" class="boxSearchForecast">
        <li class="clearfix">Hãy nhập từ khóa</li>
    </ul>
</div>


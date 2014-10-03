<ul id="userConnectPager" class="<?php echo $paging['model']; ?>">
<?php

$pageCount = ceil($paging['count'] / $paging['limit']);
$page = $paging['page'];

//print $paging['page'];
//print $page;
//die;

$sum = 0;
for ($i=0; $i < $pageCount; $i++) {
    $sum += $paging['limit'];

    if ($i == ($page - 1)) {
	    echo '<li class="now">'.($i + 1).'</li>';
    } else {
	    echo '<li><a href="javascript:scrollTo(0,0);">'.($i + 1).'</a></li>';
    }
}
?>
</ul>
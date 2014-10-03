<?php
$myTagList = array();
if (isset($myTags)) {
    foreach ($myTags as $key => $value) {
        $myTagList[] = $value['Tag']['id'];
    }
}
?>
<section class="boxTag clearfix">
    <p class="ttlSub ttlSub_bkgBk ttlPickUpUser">Tag phổ biến</p>
    <ul class="l-sideBox-body clearfix">
        <?php foreach ($popTags as $key => $value): ?>
        <li class="<?php echo (in_array($value['Tag']['id'], $myTagList) ? 'bkgCate_s' : 'bkgCate_b'); ?>">
            <a href="/tags/<?php echo $value['Tag']['name']; ?>" title="<?php echo $value['Tag']['name']; ?>" val="<?php echo $value['Tag']['name']; ?>"><?php echo $value['Tag']['name']; ?></a>
            <div class="boxCateR" style="left: -292px; display: none;">
                <p class="ttlCate">
                    <span class="txtQuestion"><?php echo $value['Tag']['question_counter']; ?> câu hỏi</span>
                </p>
                <span class="txtCate"><?php echo $value['Tag']['explain']; ?></span>
            </div>
        </li>
        <?php endforeach ?>

    </ul>
</section>
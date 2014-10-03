<?php
/**
 * @author Mai Nhut Tan
 * @since 2013/09/30
 *
 * @var array $tagList
 * @var array $myTagList
 * @var string $title
 */

if(empty($tagList)) return;

if(empty($myTagList)){
    $myTagList = array();
}

if(empty($title)){
    $title = 'Câu hỏi có tag liên quan';
}

?>
<section class="boxTag">
<ul class="clearfix">
    <?php
            foreach($tagList as $tag){
                if(!empty($tag['Tag'])){
                    $is_mine = in_array($tag['Tag']['id'], $myTagList);
                    $ctag = ($is_mine ? 'bkgCate_s' : 'bkgCate_b');
                    echo "<li class=\"$ctag\">";
                    $link = array(
                            'title' => $tag['Tag']['name'],
                            'val'   => $tag['Tag']['name']
                            );
                    if ($tag['Tag']['question_counter'] <= 0) {
                        $link['class'] = 'tagLinkDisabled';
                    }
                    echo $this->Html->link(
                        $tag['Tag']['name'],
                        '/tags/' . urlencode($tag['Tag']['name']),
                        $link
                    );
                    echo '<div class="boxCate">';
                    echo '<p class="ttlCate">';
                    echo '<span class="txtQuestion">' . $tag['Tag']['question_counter'] . ' câu hỏi</span>';
                    echo '</p>';
                    echo '<span class="txtCate">' .  $tag['Tag']['explain'] . '</span>';
                    echo '</div>';
                    echo "</li>\n";
                }
            }
        ?>
</ul>
</section>

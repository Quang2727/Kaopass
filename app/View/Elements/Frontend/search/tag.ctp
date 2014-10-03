<?php
if (empty($tag_list)) {
?>
<p class="txt0number">Không có kết quả tìm kiếm phù hợp với tag 「<?php echo h($name); ?>」.<br> Hãy thay đổi từ khóa và tìm lại.</p>
<?php
} else {
    $login_url='javascript:void(0)';
?>
    <ul>
<?php 
    foreach ($tag_list as $i => $tag_item) {
        $registed = false;
        $li_class = '';
        if(in_array($tag_item['Tag']['id'], $mytag_list)){
            $registed = true;
            $li_class = 'boxMytagOn';
        } 
?>
        <li class="<?php echo $li_class; ?>">
            <dl>
                <dt class="ttlMyTag">
<?php
/*
                    $my_tag_img = '<span class="'.$login_class.'" data-id="'.$tag_item['Tag']['id'].'">'.$this->Html->image('tags/btnMytag.png', array('alt' => __('mytag'))).'</span>';
                    if(in_array($tag_item['Tag']['id'], $mytag_list)){
                        echo $my_tag_img;
                    }else{
                        echo $this->Html->link(
                            $my_tag_img,
                            $login_url,
                            array(
                                'escape' => false,
                            )
                        );
                    }
*/
                    $tag_name = '<span class="txtMyTag">'.h($tag_item['Tag']['name']).'</span>';
                    if (isset($this->request->params['ref']) ||
                        $tag_item['Tag']['question_counter'] <= 0) {
                        echo $tag_name;
                    } else {
                        echo $this->Html->link(
                            $tag_name, 
                            '/tags/'.urlencode($tag_item['Tag']['name']), 
                            array(
                                'escape' => false,
                            )
                        );
                    }
?>
                </dt>
                <?php
                    $explain = $tag_item['Tag']['explain'];
                    if(mb_strlen($explain) > 45){
                        $explain = mb_substr($explain, 0, 45).'...';
                    }

                ?>
                <dd class="txtCont"><?php echo h($explain); ?></dd>
                <dd class="txtNumber"><?php echo !empty($tag_item['Tag']['question_counter']) ? number_format($tag_item['Tag']['question_counter']) : 0; ?> câu hỏi</dd>
                <dd>
<?php 
                    if(isset($User)) {
                      $my_tag_img = '<span class="btnMyTag" data-id="'.$tag_item['Tag']['id'].'">';
                      if ($registed === true) {
                        $my_tag_class = "btnMyTagCheck";
                        $tag_image = "/img/tags/btnMytag_active.png";
                      } else {
                        $my_tag_class = "btnMyTagBlank";
                        $tag_image = "/img/tags/btnMytag.png";
                      }
                      $my_tag_img .= '<img src="'.$tag_image.'" class="'.$my_tag_class.'" /></span>';
                      echo $this->Html->link(
                          $my_tag_img,
                          $login_url,
                          array(
                              'escape' => false,
                          )
                      );
                    }
?>
                </dd>
            </dl>
        </li>
<?php } ?>
    </ul>
<?php
    //TODO もっと見るは今回のフェーズでは実装しない
    //<p class="btnMore"><a href="/"><img src="/img/common/btnMore.png" alt="もっと見る"></a></p> 
}
?>

<?php
if(isset($this->request->params['paging']['Tag'])) {
    $paging = $this->request->params['paging']['Tag'];
?>

<div class="boxPager clearfix">
    <p class="page_counter">
<?php
//default paging object
$start_index = ($paging['page'] - 1) * $paging['limit'] + 1;
$end_index = $start_index + $paging['current'] - 1;
?>
<?php echo $start_index ?> - <?php echo $end_index ?> trong <?php echo $paging['count']; ?> kết quả
    </p>
        <ul id="userPager">        
    <?php if ($paging['pageCount'] > 1): ?>
<?php 
if (!isset($modulus)) {
    $modulus = 10;
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
        echo $this->Html->tag('li', $this->Html->link($i, '/tags?page='.$i));
    }
}

?>
            
    <?php endif; ?>            
        </ul>            
</div>
<?php } ?>

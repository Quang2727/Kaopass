<?php
/**
 * @author Mai Nhut Tan
 * @since 2013/09/27
 *
 * @var array $questionData
 * @var array $myTagList
 *
 * @todo
 */
if (empty($questionData))
    return false;

if (empty($myTagList)) {
    $myTagList = array();
}

$question_id = $questionData['Question']['id'];
$title_text = $this->String->make_title_for_list(h($questionData['Question']['title']), QUESTION_SUMMARY_TITLE_LENGTH);
//$summary_text = $this->String->getShortText($questionData['Question']['body_str'], QUESTION_SUMMARY_BODY_LENGTH);
$summary_text = $this->String->getSimpleText($questionData['Question']['body_str'], QUESTION_SUMMARY_BODY_LENGTH);

$relative_modified_date = $this->String->displayPostTime($questionData['Question']['modified']);
$formated_modified_date = date(DATETIME_FORMAT, strtotime($questionData['Question']['modified']));

$ranking = isset($questionData['clip_num']) ? $questionData['clip_num'] : 0;
?>

<li class="boxItem clearfix">
<?php

$page_view = isset($questionData['QuestionPvCount']['pv_counter']) ? $questionData['QuestionPvCount']['pv_counter'] + 0 : 0;
$replies = empty($questionData['reply_num']) ? 0 : $questionData['reply_num'];
$clips = empty($questionData['clip_num']) ? 0 : $questionData['clip_num'];

$txtKnowClass = ($ranking >0) ? '' : ' txt0Number';

$repliesClass = ($replies >0) ? '' : ' txt0Number';
$clipsClass = ($clips >0) ? '' : ' txt0Number';
$pageViewClass = ($page_view >0) ? '' : ' txt0Number';

?>
    <div class="boxItemData">
        <?php if ($questionData['Question']['accepted_flag'] == REPLY_BEST_ANSWER) { ?><p class="txtResolution"><span>Xong</span></p><?php } else { ?><p class="txtAccepting"><span>Đợi</span></p><?php } ?>
    </div>
    <div class="boxItemContent">
        <h2 class="ttlItem"><?php echo '<a href="/questions/' . $question_id . '" title="' . $title_text . '" target="">' . $title_text . '</a>'; ?></h2>
</li>

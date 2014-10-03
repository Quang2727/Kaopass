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
    <?php if ($questionData['Question']['accepted_flag'] == REPLY_BEST_ANSWER) { ?>
        <p class="entry-stateLavel is-resolved">Xong</p>
        <dl class="entry-res is-resolved<?php echo $repliesClass; ?>">
            <dt>Trả lời</dt>
            <dd><?php echo $replies; ?></dd>
        </dl>
    <?php } else { ?>
        <p class="entry-stateLavel is-accepting">Đợi</p>
        <dl class="entry-res is-accepting<?php echo $repliesClass; ?>">
            <dt>Trả lời</dt>
            <dd><?php echo $replies; ?></dd>
        </dl>
    <?php } ?>
    </div>
    <div class="boxItemContent">
        <h2 class="ttlItem"><?php echo '<a href="/questions/' . $question_id . '" title="' . $title_text . '" target="_blank">' . $title_text . '</a>'; ?></h2>

        <?php if (empty($title_only)): ?>
        <div class="txtHiddenQuestion">
            <p class="txt"><?php echo $summary_text; ?></p>
        </div>

        <?php endif; ?>

<?php
    $questionTagList = ((isset($questionData['QuestionTag']) && $questionData['QuestionTag']) ? $questionData['QuestionTag'] : array());
    $myTagList = $userTags;

if (empty($userTags)) {
    $userTags = $myTagList;
}
?>

<div class="boxRelevanceTag arrow_box clearfix">
  <ul class="entry-dataList">
      <li class="txtClip<?php echo $clipsClass; ?>"><?php echo $clips; ?><span>Clip</span></li>
      <li class="txtView<?php echo $pageViewClass; ?>"><?php echo $page_view; ?><span>Đã xem</span></li>
  </ul>
  <ul class="entry-tags boxTag">

<?php
    foreach ($questionTagList as $question_tag) {
        if (!empty($question_tag['Tag']) && is_array($userTags) === true) {
            $is_mine = in_array($question_tag['Tag']['id'], $userTags);
            $ctag = ($is_mine ? 'bkgCate_s' : 'bkgCate_b');
            echo "<li class=\"$ctag\">";
            echo $this->Html->link(
                    $question_tag['Tag']['name'],
                    '/tags/'.urlencode($question_tag['Tag']['name'])
                    , array(
                'val' => $question_tag['Tag']['name'],
                //'title' => $question_tag['Tag']['name']
                    )
            );
?>
       <div class="boxCate">
         <p class="ttlCate">
           <span class="txtQuestion"><?php echo $question_tag['Tag']['question_counter'] . ' câu hỏi';?></span>
         </p>
         <span class="txtCate"><?php echo h($question_tag['Tag']['explain']);?></span>
       </div>

<?php
            echo "</li>\n";
        }
    }
?>
    </ul>


<?php
    $userData = $questionData['User'];
    $idQuestion = $questionData['Question']['id'];
    $is_owner = true;
?>
<?php
if (empty($userData))
    $userData = array(
        'display_name' => '&nbsp;',
        'photo' => '',
        'delete_flag' => FLAG_DELETED,
    );

if (isset($userData['User'])) {
    $tmp = $userData['User'];
    unset($userData['User']);
    $userData = array_merge($tmp, $userData);
    unset($tmp);
}

$user_link = array(
    'controller' => 'users',
    'action' => 'info',
    'username' => $userData['display_name'],
);
$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];    
?>


<div class="boxItemThumb">
    <!-- <p class="boxRadius_22 floatL"> -->
    <?php
        if ($userData['delete_flag'] === FLAG_NOT_DELETED) {
            echo $this->Html->link($this->Html->image($img_src),$user_link,array('class' => 'icnUserThumb_22', 'alt' => $userData['display_name'], 'escape'=>false));
        } else {
            echo $this->Html->image($img_src);
        }

    ?>
    <!-- </p> -->
    <span class="txtUserName">
    <?php
        if ($userData['delete_flag'] === FLAG_NOT_DELETED) {
            echo $this->Html->link(
                $userData['display_name'],
                $user_link,
                array(
                    'title' =>  $this->String->convertURL($userData['display_name'])
                )
            );
        } else {
            echo $userData['display_name'];
        }
    ?>
    </span>
    <span class="txttUpdate"><?php echo $relative_modified_date; ?></span>
</div>
</li>

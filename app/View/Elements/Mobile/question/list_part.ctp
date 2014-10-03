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

<?php

$page_view = isset($questionData['QuestionPvCount']['pv_counter']) ? $questionData['QuestionPvCount']['pv_counter'] + 0 : 0;
$replies = empty($questionData['reply_num']) ? 0 : $questionData['reply_num'];
$clips = empty($questionData['clip_num']) ? 0 : $questionData['clip_num'];

$txtKnowClass = ($ranking >0) ? '' : ' txt0Number';

$repliesClass = ($replies >0) ? '' : ' txt0Number';
$clipsClass = ($clips >0) ? '' : ' txt0Number';
$pageViewClass = ($page_view >0) ? '' : ' txt0Number';

?>
<li class="boxItem clearfix">
  <h2 class="ttlItem-entry"><?php echo '<a href="/questions/' . $question_id . '" title="' . $title_text . '">' . $title_text . '</a>'; ?></h2>


<?php
if (!empty($questionData['QuestionTag'])) {
    $questionTagList = $questionData['QuestionTag'];
    $myTagList = $userTags;

if (empty($questionTagList))
    return false;

if (empty($userTags)) {
    $userTags = $myTagList;
}
?>

  <ul class="boxTag boxTag-wrap clearFix">
<?php
    foreach ($questionTagList as $question_tag) {
      if (!empty($question_tag['Tag']) && is_array($userTags) === true) {
        $is_mine = in_array($question_tag['Tag']['id'], $userTags);
        $ctag = ($is_mine ? 'bkgCate_s' : 'bkgCate_b');
        echo "<li class=\"$ctag boxTag-list\">";
        echo $this->Html->link(
              $question_tag['Tag']['name'],
              '/tags/'.urlencode($question_tag['Tag']['name'])
              , array(
                'val' => $question_tag['Tag']['name'],
                'class' => 'boxTag-block',
              )
        );
?>
<?php
      }
    }
?>
  </ul>
<?php } ?>

  
<?php
    $userData = $questionData['User'];
    $idQuestion = $questionData['Question']['id'];
    $is_owner = true;
    $login_user_id = isset($login_user_id) ? $login_user_id : '';

if (isset($userData['User'])) {
    $tmp = $userData['User'];
    unset($userData['User']);
    $userData = array_merge($tmp, $userData);
    unset($tmp);
}

if(!empty($login_user_id)) {
    $login_user_id == $userData['id'];
}

$user_link = array(
    'controller' => 'users',
    'action' => 'info',
    'username' => $userData['display_name'],
);
$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$userData['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$userData['photo'];    
?>
  <div class="boxItemThumb">
    <span class="txtUserName">
    <?php
      echo $this->Html->link(
        ''.$userData['display_name'].'',
        $user_link,
        array(
          'title' =>  $this->String->convertURL($userData['display_name'])
        )
      );
    ?></span>
    <span class="txtUpdate"><?php echo $relative_modified_date; ?></span>
  </div>

  <div class="boxItemData is-pt20">
    <p class="boxItemData-list txtAnswer<?php echo $repliesClass; ?>"><span class="boxItemData-num"><?php echo $replies; ?></span>回答</p>
    <p class="boxItemData-list txtClip<?php echo $clipsClass; ?>"><span class="boxItemData-num"><?php echo $clips; ?></span>クリップ</p>
    <p class="boxItemData-list txtView<?php echo $pageViewClass; ?>"><span class="boxItemData-num"><?php echo $page_view; ?></span>PV</p>
  </div>
</li>


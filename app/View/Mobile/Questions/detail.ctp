<!-- highlight -->
<?php
echo $this->Html->script(array(
    'plugins/highlight.pack',
));
?>
<script>hljs.configure({useBR: false});hljs.initHighlightingOnLoad();</script>
<?php
$this->Html->css(array(
  'sp/questions/detail',
  'sp/questions/markdown',
  'sp/highlight/default',
  'sp/highlight/highlight',
   ),
   null, array(
     'inline' => false));

$this->Html->script(array(
                        'sp/questions/question_editor'
                        ,'sp/questions/detail'
                  ),array('inline' => false));
$reply_paging = $this->Element('Frontend/pagination', array('model_name' => 'Reply'));
if (isset($User['id']) === false) $User['id'] = null;
/// Thông tin câu hỏi
$clip_num = isset($clip_num) ? $clip_num : 0;
$my_vote = VOTE_NONE;
if (!empty($question['Vote'])) {
  foreach ($question['Vote'] as $voteData) {
    if ((isset($User) && $User['id'] == $voteData['user_id'])) {
      if ($voteData['vote_up'] > 0)
        $my_vote = VOTE_UP;
      else if ($voteData['vote_down'] > 0)
        $my_vote = VOTE_DOWN;
      else
        $my_vote = VOTE_NONE;
    }
  }
}

$is_owner = $is_mine = (!empty($User) && $question['Question']['user_id'] == $User['id']) ? true : false;

$page_view = isset($question['QuestionPvCount']['pv_counter']) ? $question['QuestionPvCount']['pv_counter'] + 0 : 0;
$replies = empty($reply_num) ? 0 : $reply_num;

if (!empty($question['User'])) {

  $point = isset($question["User"]["UserCount"][5])?$question["User"]["UserCount"][5]:0;

  $medal_list = array(
    'question_badge' => isset($question["User"]["UserCount"][6])?$question["User"]["UserCount"][6]:0,
    'reply_badge' => isset($question["User"]["UserCount"][7])?$question["User"]["UserCount"][7]:0,
    'other_action_badge' => isset($question["User"]["UserCount"][8])?$question["User"]["UserCount"][8]:0,
  );

}

$title_text = $question['Question']['title'];
$body_text = $question['Question']['body_str'];

//$body_text = preg_replace("/[\r|\n]/", "<br>", $body_text);
$relative_modified_date = $this->String->displayPostTime($question['Question']['modified']);

$userData = $question['User'];

$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$question['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$question['User']['photo'];
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
$keyword_tags = '';
foreach($question['QuestionTag'] as $questionTag) {
    $keyword_tags .= $questionTag["Tag"]["name"].',';
}
$this->end();?>
  <div class="questionHeadWrap" class="clearfix">
    <div class="questionHead">
      <h1 class="ttlItem"><?php echo h($question['Question']['title']) ?></h1>
      <!--Tag liên quan　Từ đây-->
<?php if (!empty($question['QuestionTag'])) { ?>
<?php
$questionTagList = $question['QuestionTag'];
$myTagList = $userTags;
if (empty($userTags)) {
    $userTags = $myTagList;
}
?>
      <div class="boxTag">
        <ul class="boxTag-wrap clearFix">
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
      }
  }
?>

        </ul>
      </div>
<?php } ?>
      <!--Tag liên quan　đến đây-->
      <div class="boxItemData clearFix">
        <p class="boxItemData-list txtAnswer<?php if ($replies == 0) echo " txt0Number"; ?>"><span class="boxItemData-num"><?php echo $replies; ?></span>Giải đáp</p>
        <p class="boxItemData-list txtClip<?php if ($clip_num == 0) echo " txt0Number"; ?>"><span class="boxItemData-num"><?php echo $clip_num; ?></span>Clip</p>
        <p class="boxItemData-list txtView<?php if ($page_view == 0) echo " txt0Number"; ?>"><span class="boxItemData-num"><?php echo $page_view; ?></span>PV</p>
      </div>
      <div class="boxQuestioner arrow_box writerSts clearFix">
        <div class="boxStat clearFix">
          <div class="boxStatThumb">
            <p class="boxRadius_48">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                <a href="/users/<?php echo h($userData['display_name']);?>">
<?php endif; ?>
                    <?php echo $this->Html->image($img_src, array('alt' => __($userData['display_name']), 'class'=>'icnUserThumb_22', 'style' => array('width:25px;height:25px')));?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                </a>
<?php endif; ?>
            </p>
          </div>
          <div class="boxUserInfo">
            <p class="txtUserName">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                <a href="/users/<?php echo h($userData['display_name']);?>">
<?php endif; ?>
                    <?php echo h($userData['display_name']);?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                </a>
<?php endif; ?>
            </p>
            <div class="boxUserPoint">
              <img alt="Huy hiệu liên quan đến câu hỏi" src="/img/sp/common/icnBatch_question.png" width="10" height="10"><?php echo $medal_list['question_badge']; ?>
              <img alt="Huy hiệu liên quan đến đáp án" src="/img/sp/common/icnBatch_answer.png" width="10" height="10"><?php echo $medal_list['reply_badge']; ?>
              <img alt="Huy hiệu liên quan đến các hoạt động khác" src="/img/sp/common/icnBatch_action.png" width="10" height="10"><?php echo $medal_list['other_action_badge']; ?>
            </div>
          </div>
        </div>
      </div>
      <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($question['Question']['created']));?></p>
    </div>
  </div>
  <div class="content">
  <!-- ▼Chi tiết của câu hỏi start -->
  <div class="boxQuestionDetail">
    <div class="wrapQuestionDetail">
      <section class="boxItem resolved">
        <div class="boxQuestionInner clearfix">
          <div class="boxQuestionInnerLeft">
            <!--Nội dung　Từ đây-->
            <div class="boxItemContentDetail"><?php echo $question['Question']['body_str'];?></div>
            <!--Nọi dung　Đến đây---->
            <div class="boxItemDetailFooter">
              <!-- Nút bạn muốn biết-->
              <div class="boxUnderstandWant clearfix">
                <?php
                  if(!$is_owner) {
                    if(isset($user_id) && !empty($user_id)) {
                      $class = ($my_vote == VOTE_UP ? 'btnUnderstandWant_on' : 'btnUnderstandWant');
                    } else {
                      $class = 'btnModalLogin btnUnderstandWant';
                    }
                  } else {
                    $class = 'is-btnClip-disabled';
                  }
                ?>
                <p><button class="mod-btn mod-btnClip <?php echo $class;?>" value="<?php echo ($my_vote == VOTE_UP ? VOTE_NONE : VOTE_UP); ?>" type="image" key="<?php echo $question['Question']['id']; ?>">Gắn Clip cho câu hỏi này</button></p>
                <p class="understandCount"><span class="txtUnderstandWant"><?php echo $clip_num; ?></span></p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <!------------Chi tiết của câu hỏi end ------------------- --->
<?php if (!empty($replys)) { ?>
<?php
foreach ($replys as $key => $replyData) {
  $ranking = $replyData['Reply']['vote_up'];
  $my_vote = VOTE_NONE;
  if (isset($User['id']) && isset($replyVotes[$replyData['Reply']['id']])) {
    list($_vote_up, $_vote_down) = each($replyVotes[$replyData['Reply']['id']]);

    if ($_vote_up > 0)
      $my_vote = VOTE_UP;
    elseif ($_vote_down > 0)
      $my_vote = VOTE_DOWN;
    else
      $my_vote = VOTE_NONE;

  }

  $reply_id = $replyData['Reply']['id'];
  $summary_text = $replyData['Reply']['body_str'];

  $medal_list = array(
    'question_badge' => isset($replyData['User']["UserCount"][6])?$replyData['User']["UserCount"][6]:0,
    'reply_badge' => isset($replyData['User']["UserCount"][7])?$replyData['User']["UserCount"][7]:0,
    'other_action_badge' => isset($replyData['User']["UserCount"][8])?$replyData['User']["UserCount"][8]:0,
  );
  $userData = $replyData['User'];
  $relative_modified_date = $this->String->displayPostTime($replyData['Reply']['modified']);  

  $point = isset($replyData['User']["UserCount"][5])?$replyData['User']["UserCount"][5]:0;
  $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$replyData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$replyData['User']['photo'];
?>
<?php if ($replyData['Reply']['best_answer_flag'] == 1) { ?>

  <!------------▼Câu trả lời hay nhất start ------------>
  <!---- boxItem start --- --->
  <h2 class="ttlSub ttlBestAnswer">Câu trả lời hay nhất</h2>
  <div id="boxBestAnswer">
    <div class="boxItemContentDetail">
      <p class="question-preview"><?php echo $summary_text; ?></p>
    </div>
    <!---- boxItem start --- --->
    <section class="boxItem">
<?php if ($User['id'] != $replyData['Reply']['user_id']) { ?>
<?php $bottonClass = ($my_vote == VOTE_UP) ? "btnUnderstand_on" : "btnUnderstand"; ?>
<?php $class = (isset($user_id) && !empty($user_id)) ? ' btnUnderstand01'  : ' btnModalLogin'; ?>
      <div class="boxUnderstand">
        <p class="btnUnderstandWrap">
          <button class="<?php echo $bottonClass.$class; ?>" value="<?php echo ($my_vote == VOTE_UP ? VOTE_NONE : VOTE_UP); ?>" key="<?php echo $replyData['Reply']['id']; ?>">＋</button><span class="btnUnderstandCount txtPoints"><?php echo $ranking; ?></span></p>
      </div>
<?php } ?>
      <div class="boxItemDetailHeader">
        <div class="writerSts clearfix">
          <div class="boxStat">
            <div class="boxStatThumb">
              <p class="boxRadius_48">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  <a href="/users/<?php echo $userData['display_name'];?>">
<?php endif; ?>
                      <?php echo $this->Html->image($img_src, array('alt' => __($userData['display_name']), 'class'=>'icnUserThumb_22','style' =>'width:25px;height:25px;'));?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </p>
            </div>
            <div class="boxUserInfo">
              <p class="txtUserName">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  <a href="/users/<?php echo $userData['display_name'];?>">
<?php endif; ?>
                      <?php echo $userData['display_name'];?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </p>
              <div class="boxUserPoint">
                <img alt="Huy hiệu liên quan đến câu hỏi" src="/img/sp/common/icnBatch_question.png" width="10" height="10"><?php echo $medal_list['question_badge']; ?>
                <img alt="Huy hiệu liên quan đến đáp án" src="/img/sp/common/icnBatch_answer.png" width="10" height="10"><?php echo $medal_list['reply_badge']; ?>
                <img alt="Huy hiệu liên quan đến các hoạt động khác" src="/img/sp/common/icnBatch_action.png" width="10" height="10"><?php echo $medal_list['other_action_badge']; ?>
              </div>
            </div>
            <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['created']));?></p>
          </div>
        </div>
      </div>
    <!---- boxItem start --- --->
<?php //echo (isset($userData["UserVoteScore"]["total"]))? $userData["UserVoteScore"]["total"] : ""; ?>
      <div class="boxItemDetailFooter">
        <div class="boxAnswerCommentList" id="rc480">
          <p class="ttlAnswerComment" disabled="">Bình luận về đáp án này（<?php echo isset($replyData['Comments']["total_count"]) ? $replyData['Comments']["total_count"] : 0; ?>）</p>
<?php
  if(isset($replyData['Comments']['list'])) {
?>
          <div class="boxAnswerComment">
<?php
  foreach ($replyData['Comments']['list'] as $commentData) {
?>
            <div class="boxCommentContent arrow_box_b markdown-area question-preview">
              <p class="boxItemContentDetail question-preview"><?php echo nl2br(h($commentData['comment_str'])); ?></p>
            </div>
            <dl class="boxStat clearfix">
              <dt class="boxStatThumb">
                <p class="commentUpdate"><?php echo date('Y/m/d H:i', strtotime($commentData['created']));?></p>
              </dt>
              <dd class="boxUser">
<?php if(FLAG_NOT_DELETED === $commentData['User']['delete_flag']) : ?>
                  <a href="/users/<?php echo $commentData['User']['display_name'];?>">
<?php endif; ?>
                      <p class="txtUserName"><?php echo $commentData['User']['display_name'];?></p>
<?php if(FLAG_NOT_DELETED === $commentData['User']['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </dd>
            </dl>

<!-- Bình luận end -->
<?php
  }
?>
          </div>
<?php
  }
?>
        </div>
      </div>
    </section>
  </div>
  <!------------Câu trả lời hay nhấtー end ---------------->

<?php } ?>
  <!------------▼Những câu trả lời liên quan khác start ------------>
<?php if($key == 0 ) { ?>
<?php if ($replyData['Reply']['best_answer_flag'] == 1) { ?>
  <!------------▼Những câu trả lời liên quan khác start ------------>
  <?php if (count($replys) > 1) { ?>
  <h2 class="ttlSub ttlOtherAnswer">Đáp án khác</h2>
  <div id="boxOtherAnswer">
  <?php } ?>
<?php } else { ?>
  <!------------▼Những câu trả lời liên quan khác start ------------>
  <h2 class="ttlSub ttlOtherAnswer">Danh sách đáp án</h2>
  <div id="boxOtherAnswer">
<?php } ?>
<?php } ?>
<?php if($replyData['Reply']['best_answer_flag'] != 1) { ?>
    <!---- boxItem start --- --->
    <section class="boxItem">
      <div class="boxItemContentDetail question-preview markdown-area">
        <p><?php echo $summary_text; ?></p>
      </div>
      <?php $bottonClass = ($my_vote == VOTE_UP || $User['id'] == $replyData['Reply']['user_id']) ? "btnUnderstand_on" : "btnUnderstand"; ?>
      <?php $class = (isset($user_id) && !empty($user_id)) ? ' btnUnderstand01'  : ' btnModalLogin'; ?>
      <div class="boxUnderstand">
        <p class="btnUnderstandWrap">
          <button class="<?php echo $bottonClass.$class; ?>" value="<?php echo (($my_vote == VOTE_UP || $User['id'] == $replyData['Reply']['user_id']) ? VOTE_NONE : VOTE_UP); ?>" key="<?php echo $replyData['Reply']['id']; ?>"></button>
          <span class="btnUnderstandCount txtPoints"><?php echo $ranking; ?></span></p>
      </div>
      <div class="boxItemDetailHeader">
        <div class="writerSts clearfix">
          <div class="boxStat">
            <div class="boxStatThumb">
              <p class="boxRadius_48">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  <a href="/users/<?php echo h($userData['display_name']);?>">
<?php endif; ?>
                      <?php echo $this->Html->image($img_src, array('alt' => __($userData['display_name']), 'class'=>'icnUserThumb_22', 'style'=>"width:25px;height:25px"));?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </p>
            </div>
            <div class="boxUserInfo">
              <p class="txtUserName">
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  <a href="/users/<?php echo h($userData['display_name']);?>">
<?php endif; ?>
                      <?php echo h($userData['display_name']);?>
<?php if(FLAG_NOT_DELETED === $userData['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </p>
              <div class="boxUserPoint">
                <img alt="Huy hiệu liên quan đến câu hỏi" src="/img/sp/common/icnBatch_question.png" width="10" height="10"><?php echo $medal_list['question_badge']; ?>
                <img alt="Huy hiệu liên quan đến đáp án" src="/img/sp/common/icnBatch_answer.png" width="10" height="10"><?php echo $medal_list['reply_badge']; ?>
                <img alt="Huy hiệu liên quan đến các hoạt động khác" src="/img/sp/common/icnBatch_action.png" width="10" height="10"><?php echo $medal_list['other_action_badge']; ?>
              </div>
            </div>
            <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['created']));?></p>
          </div>
        </div>
      </div>
      <div class="boxItemDetailFooter">
        <div class="boxAnswerCommentList" id="rc480">
          <p class="ttlAnswerComment" disabled="">Bình luận về câu trả lời này（<?php echo isset($replyData['Comments']["total_count"]) ? $replyData['Comments']["total_count"] : 0; ?>）</p>
<?php
  if(isset($replyData['Comments']['list']) && !empty($replyData['Comments']['list'])) {
?>
          <div class="boxAnswerComment">
<?php
  foreach ($replyData['Comments']['list'] as $commentData) {
?>


            <div class="boxCommentContent arrow_box_b markdown-area question-preview">
              <p class="boxItemContentDetail question-preview"><?php echo nl2br(h($commentData['comment_str'])); ?></p>
            </div>
            <dl class="boxStat clearfix">
              <dt class="boxStatThumb">
                <p class="commentUpdate"><?php echo date('Y/m/d H:i', strtotime($commentData['created']));?></p>
              </dt>
              <dd class="boxUser">
<?php if(FLAG_NOT_DELETED === $commentData['User']['delete_flag']) : ?>
                  <a href="/users/<?php echo h($commentData['User']['display_name']);?>">
<?php endif; ?>
                      <p class="txtUserName"><?php echo h($commentData['User']['display_name']);?></p>
<?php if(FLAG_NOT_DELETED === $commentData['User']['delete_flag']) : ?>
                  </a>
<?php endif; ?>
              </dd>
            </dl>
<!-- Bình luận end -->
<?php } ?>
          </div>
<?php } ?>
        </div>
        <p class="btnUnderstandWrap">
      </div>
    </section>
<?php } ?>
<?php } ?>
  </div>
<?php } ?>

<?php if(isset($user_id) && !empty($user_id) && !$is_owner) { ?>
<h2 class="ttlSub ttlYourAnswer">Câu trả lời của bạn</h2>
<div class="boxYourAnswer">
    <?php echo $this->Element('Mobile/forms/comment',array('comment_flg' => 0, 'placeholder' => '回答を入力してください')); ?>
<?php } ?>
<?php if(!(isset($user_id) && !empty($user_id))) { ?>
    <button class="btnModalLogin mod-btn mod-btnBlue mod-btnNotLogin">Đăng nhập/Đăng ký mới và trả lời</button>
<?php } ?>
</div>
  <p class="boxGoToTop"><a href="/">Quay về Top Page</a></p>
</div>


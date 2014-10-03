<?php $fullPath = $this->Html->url(null, true); ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&appId=313209618817050&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript"> var CONTRIBUTED_IMAGES_DIR = <?php echo "'".$image_path."'"; ?></script>
<?php
$this->start('meta');
echo $this->Html->meta(array(
    'property' => 'og:title',
    'content' => strip_tags($title_for_layout),
));
echo $this->Html->meta(array(
    'property' => 'og:type',
    'content' => 'article',
));
echo $this->Html->meta(array(
    'property' => 'og:description',
    'content' => $description_for_layout,
));
echo $this->Html->meta(array(
    'property' => 'og:url',
    'content' => $fullPath,
));
echo $this->Html->meta(array(
    'property' => 'og:image',
    'content' => "https://".$_SERVER['HTTP_HOST']."/img/imgFacebookShare.png",
));
echo $this->Html->meta(array(
    'property' => 'og:site_name',
    'content' => 'teratail',
));
echo $this->Html->meta(array(
    'property' => 'og:locale',
    'content' => 'ja',
));
echo $this->Html->meta(array(
    'property' => 'fb:admins',
    'content' => '313209618817050',
));
echo $this->Html->meta(array(
    'property' => 'fb:app_id',
    'content' => '313209618817050',
));
$this->end();
?>
<?php
$this->Html->css(array(
  'questions/questions_new',
  'questions/detail_new',
  'plugins/bootstrap-markdown.min',
  'plugins/bootstrap-tagsinput',
  'questions/input',
   ),
   null, array(
     'inline' => false));

$this->Html->script(array('frontend/js_popup'), array('inline' => false));
$this->Html->script(array('boxLabel'), array('inline' => false));
$this->Html->script(array('frontend/question_editor',),array('inline' => false));
$this->Html->css(array('questions/markdown.css'), null, array('inline' => false));
$this->Html->css(array('highlight/default'), null, array('inline' => false));
$this->Html->css(array('highlight/highlight'), null, array('inline' => false));

$reply_paging = $this->Element('Frontend/pagination', array('model_name' => 'Reply'));


if (isset($User['id']) === false) $User['id'] = null;

/// 質問情報
$questionData = $question;
$ranking = isset($clip_num) ? $clip_num : 0;
$my_vote = VOTE_NONE;
if (!empty($questionData['Vote'])) {
  foreach ($questionData['Vote'] as $voteData) {
//    $ranking += $voteData['vote_up'];
    if (( isset($User) && $User['id'] == $voteData['user_id'])) {
      if ($voteData['vote_up'] > 0)
        $my_vote = VOTE_UP;
      else if ($voteData['vote_down'] > 0)
        $my_vote = VOTE_DOWN;
      else
        $my_vote = VOTE_NONE;
    }
  }
}

$is_owner = $is_mine = (!empty($User) && $questionData['Question']['user_id'] == $User['id']) ? true : false;
$isAdministrator = (!empty($User) && isset($User['user_type']) && $User['user_type'] == 1) ? true : false;

$page_view = isset($questionData['QuestionPvCount']['pv_counter']) ? $questionData['QuestionPvCount']['pv_counter'] + 0 : 0;
$replies = empty($reply_num) ? 0 : $reply_num;

if (!empty($questionData['User'])) {

  $point = isset($questionData["User"]["UserCount"][5])?$questionData["User"]["UserCount"][5]:0;

  $medal_list = array(
    'question_badge' => isset($questionData["User"]["UserCount"][6])?$questionData["User"]["UserCount"][6]:0,
    'reply_badge' => isset($questionData["User"]["UserCount"][7])?$questionData["User"]["UserCount"][7]:0,
    'other_action_badge' => isset($questionData["User"]["UserCount"][8])?$questionData["User"]["UserCount"][8]:0,
  );

}

$title_text = h($questionData['Question']['title']);
$body_text = $questionData['Question']['body_str'];

//$body_text = preg_replace("/[\r|\n]/", "<br>", $body_text);
$relative_modified_date = $this->String->displayPostTime($questionData['Question']['modified']);

$userData = $questionData['User'];

$img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$questionData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$questionData['User']['photo'];
?>
    <div id='loading'></div>


<div id="QuestionHeadWrap" class="clearfix">
    <div id="QuestionHead">
        <h1 class="ttlItem">
        <?php if ($questionData['Question']['accepted_flag'] == REPLY_BEST_ANSWER) { ?>
            <span class="txtResolution">Đã giải quyết</span>
        <?php } else {?>
            <span class="txtAccepting">Chờ đáp án</span>
        <?php } ?>
        <?php echo $title_text; ?>
        </h1>

        <div class="clearfix">
            <!--関連タグ　ここから-->
            <?php if (!empty($questionData['QuestionTag'])) { ?>
            <?php
            $questionTagList = $questionData['QuestionTag'];
            $myTagList = $userTags;
            if (empty($questionTagList))
                return false;
            if (empty($userTags)) {
                $userTags = $myTagList;
            }
            ?>
            <div class="boxRelevanceTag arrow_box floatL">
                <div class="boxTag clearFix">
                    <ul>
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
                                    'val' => $question_tag['Tag']['name']
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
                </div>
            </div>
            <?php } ?>
            <!--関連タグ　ここまで-->
            <div class="boxQuestioner arrow_box writerSts floatR">
                <div class="boxStat">
                    <div class="boxStatThumb clearFix">
                        <p class="boxRadius_48">
                            <?php if ($userData['delete_flag'] === 0):?>
                                <a href="/users/<?php echo h($userData['display_name']);?>">
                                    <?php echo $this->Html->image($img_src, array('alt' => __($userData['display_name']), 'class'=>'icnUserThumb_22'));?>
                                </a>
                            <?php else: ?>
                                <?php echo $this->Html->image($img_src, array('alt' => __($userData['display_name']), 'class'=>'icnUserThumb_22'));?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <p class="txtUserName">
                        <?php if ($userData['delete_flag'] === 0):?>
                        <a href="/users/<?php echo h($userData['display_name']);?>"><?php echo h($userData['display_name']);?></a>
                        <?php else: ?>
                        <?php echo h($userData['display_name']);?>
                        <?php endif; ?>
                        <span class="txtUserPoint">score <?php echo (isset($userData["UserVoteScore"]["total"]))? $userData["UserVoteScore"]["total"] : ''; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div id="boxQuestionDetail">
        <div id="wrapQuestionDetail" class="clearfix">
            <section class="boxItem resolved">
                <div class="boxQuestionInner clearfix">
                    <div class="boxQuestionInnerLeft">
                        <!--本文　ここから-->
                        <div class="boxItemContentDetail">
                            <?php echo $body_text; ?>
                        </div>
                        <!--本文　ここまで-->
                        <?php
                        if (isset($question_comments["list"]) && !empty($question_comments["list"])) {
                        ?>
                        <?php
                            foreach ($question_comments["list"] as $commentData) {
                        ?>
                        <div class="boxCommentContent arrow_box_b markdown-area question-preview clearfix">
                            <p class="ttlPostScript">Bổ sung</p>
                            <p class="updateComment"><?php echo date('Y/m/d H:i', strtotime($commentData['modified']));?></p>
                            <p class="txtComment question-preview"><?php echo nl2br(h($commentData['comment_str'])); ?></p>
                            <?php if($isAdministrator) {?>
                                <p class="txtPostscript floatL">
                                    <?php echo $this->Html->link('Xóa bổ sung cho câu hỏi',
                                    array('controller' => 'questions', 'action' => 'deletequestioncomment', $commentData['id']));?>&nbsp;&nbsp;
                                </p>
                                <p class="txtPostscript floatL"><?php echo $this->Html->link('Thay đổi bổ sung cho câu hỏi',
                                    array('controller' => 'questions', 'action' => 'editquestioncomment', $commentData['id']));?>
                                </p>
                            <?php } ?>
                        </div>
                        <?php
                            }
                        ?>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
            <div class="boxQuestionSide">
                <div class="clearfix">
                <ul class="boxEvaluation">
                    <?php if(!empty($replys)) { ?>
                        <li class="floatL boxResponses">
                    <?php } else { ?>
                        <li class="floatL boxResponses_0answer">
                    <?php } ?>
                        <p class="txtEvaluation txtResponses"><?php echo $replies; ?></p>
                        <p>Trả lời</p>
                    </li>
                    <li class="floatL boxCrip">
                        <p class="txtEvaluation txtCrip"><?php echo $ranking; ?></p>
                        <p>Clip</p>
                    </li>
                    <li class="floatL boxPV">
                        <p class="txtEvaluation txtPV"><?php echo $page_view; ?></p>
                        <p>Xem</p>
                    </li>
                </ul>
                <div class="boxUnderstandWant">
                <?php
                    if(isset($user_id) && !empty($user_id)) {
                        $style = ($my_vote == VOTE_UP ? 'btnUnderstandWant_on' : 'btnUnderstandWant');
                    } else {
                        $style = 'btnModalLogin btnUnderstandWant';
                    }
                ?>
                <?php
                    if(!$is_owner) {
                ?>
                    <p>
                        <button class="<?php echo $style;?> mod-btn mod-btnBlue" value="<?php echo ($my_vote == VOTE_UP ? VOTE_NONE : VOTE_UP); ?>"
                        type="image" key="<?php echo $questionData['Question']['id']; ?>">Theo dõi câu hỏi này</button>
                    </p>
                    <p class="understandCount"><span class="txtUnderstandWant"><?php echo $ranking; ?></span></p>
                <?php
                    }
                ?>
                </div>
                <section class="boxSnsArea">
                    <div class="btnWrap">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $fullPath; ?>?ip=n0070000_019" data-lang="ja" data-hashtags="teratail">Tweet</a>
                    </div>
                    <div class="btnWrap">
                        <div class="fb-like" data-href="<?php echo $fullPath; ?>?sip=n0040000_019" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
                    </div>
                    <div class="btnWrap">
                        <div class="g-plusone" data-href="<?php echo $fullPath; ?>?sip=n0210000_019"></div>
                    </div>
                </section>
                </div>
            </div>
        </div>
        <div class="boxEditPostscript">
            <section class="boxYourAnswer boxPost question-editor-area">
            <?php echo $this->Element('Frontend/question/comment', array(
              'type' => 1,'id' => $questionData['Question']['id'], 'placeholder' => 'Bổ sung cho câu hỏi',));?>
            </section>
        </div>
    </div>
    <div class="boxItemDetailFooter">
        <div class="l-editModules clearFix">
            <div class="boxPostscript floatL">
                <?php if($is_owner || $isAdministrator) { ?>
                <p class="txtPostscript floatL">
                    <a href="/questions/edit/<?php echo $questionData['Question']['id'];?>">
                        <button class="btnEditQuestion mod-btn mod-btnWhite">Thay đổi câu hỏi</button>
                    </a>
                </p>
                <p class="txtPostscript floatL">
                    <button class="btnDescriptionPostscript mod-btn mod-btnWhite">Bổ sung cho câu hỏi</button>
                </p>
                <?php } ?>
                <?php if($isAdministrator) { ?>
                <p class="txtPostscript floatR"><?php echo $this->Html->link('Thay đổi câu hỏi',
                   array('controller' => 'questions', 'action' => 'edit', $questionData['Question']['id']));?>
                </p>
                <p class="txtPostscript floatR"><?php echo $this->Html->link('Xóa câu hỏi',
                   array('controller' => 'questions', 'action' => 'delete', $questionData['Question']['id']));?>
                </p>
                <?php } ?>
            </div>
            <div class="boxUnderstandWant">
            <?php
                if(isset($user_id) && !empty($user_id)) {
                    $style = ($my_vote == VOTE_UP ? 'btnUnderstandWant_on' : 'btnUnderstandWant');
                } else {
                    $style = 'btnModalLogin btnUnderstandWant';
                }
            ?>
            <?php
                if(!$is_owner) {
            ?>
                <p class="floatL">
                    <button class="<?php echo $style;?> mod-btn mod-btnBlue" value="<?php echo ($my_vote == VOTE_UP ? VOTE_NONE : VOTE_UP); ?>"
                    type="image" key="<?php echo $questionData['Question']['id']; ?>">Theo dõi câu hỏi này</button>
                </p>
                <p class="understandCount"><span class="txtUnderstandWant"><?php echo $ranking; ?></span></p>
            <?php
                }
            ?>
            </div>
            <div class="boxTime">
                <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($questionData['Question']['created']));?>　Đăng bài</p>
<?php if ($questionData['Question']['created'] != $questionData['Question']['modified']): ?>
                <p class="txtUpdateTime"><?php echo date('Y/m/d H:i', strtotime($questionData['Question']['modified']));?>　Thay đổi</p>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>


    <!-- Main start -->
    <div id="wrapAnswerDetail" class="clearfix">
        <div id ="mainContainer">
            <?php if(!empty($replys)) { ?>
            <ul class="mod-sortSwitch">
<?php
$answer_sort_links = Configure::read('sort.display_name');
foreach ($answer_sort_links as $_key => $_name) {
    $is_currented = '';
    $link = '';
    if ($answer_sort == $_key) {
        $is_currented = ' class="is-currented"';
        $link = $_name;
    } else {
        $link = '<a href="javascript:void(0)" onmouseup="location.href=&quot;?sort=' . $_key . '#&quot; + window.scrollY">' . $_name . '</a>';
    }
    echo '<li', $is_currented, '>', $link, '</li>';
}
?>
<script>
if (location.hash) {
    var y = location.hash.substr(1);
    if (!isNaN(y)) {
        jQuery.event.add(window, "load", function(){scrollTo(0, y);});
    }
}
</script>
            </ul>
            <h2 class="mod-ttl is-mt0">Trả lời (<?php echo $replies; ?>)</h2>
            <?php } else { ?>
            <p class="txtNotQuestion">Chưa có trả lời cho câu hỏi này</p>
            <?php } ?>
            <div class="boxAnswers">
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
$keyword_tags = '';
foreach($questionData['QuestionTag'] as $questionTag) {
    $keyword_tags .= $questionTag["Tag"]["name"].',';
}
echo $keyword_tags.$title_text.','.$keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
    <?php if (isset($tag_info['Tag']['name'])): ?>
      <li><a href="/tags/<?php echo h(urlencode($tag_info['Tag']['name'])); ?>"><?php echo h($tag_info['Tag']['name']); ?> và câu hỏi liên quan</a></li>
    <?php endif ?>
<li><?php echo $title_text;?></li>
<?php $this->end();?>






<?php if (!empty($replys)) : ?>
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
  //$summary_text = Markdown(h($replyData['Reply']['body']));
  $summary_text = $replyData['Reply']['body_str'];
//  $summary_text = preg_replace("/[\r|\n]/", "<br>", $summary_text);
//  $relative_modified_date = $this->String->displayPostTime($replyData['Reply']['modified']);

  $medal_list = array(
    'question_badge' => isset($replyData['User']["UserCount"][6])?$replyData['User']["UserCount"][6]:0,
    'reply_badge' => isset($replyData['User']["UserCount"][7])?$replyData['User']["UserCount"][7]:0,
    'other_action_badge' => isset($replyData['User']["UserCount"][8])?$replyData['User']["UserCount"][8]:0,
  );
  $replyUserData = $replyData['User'];
  $relative_modified_date = $this->String->displayPostTime($replyData['Reply']['modified']);

  $point = isset($replyData['User']["UserCount"][5])?$replyData['User']["UserCount"][5]:0;
  $img_src = (!is_file(IMAGES.USER_AVATAR_DIR.$replyData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$replyData['User']['photo'];
?>
<?php if ($replyData['Reply']['best_answer_flag'] == 1) { ?>

            <div id="boxBestAnswer">
                <!---- boxItem start --- --->
                <section class="boxItem">
                    <h2 class="ttlBestAnswer">Câu trả lời đúng nhất</h2>
                    <div class="btnUnderstandWrap">
                        <?php if ($User['id'] != $replyData['Reply']['user_id']) { ?>
                        <?php $bottonClass = ($my_vote == VOTE_UP) ? "btnUnderstand_on" : "btnUnderstand"; ?>
                        <?php $class = (isset($user_id) && !empty($user_id)) ? ' btnUnderstand01'  : ' btnModalLogin'; ?>
                        <button class="<?php echo $bottonClass.$class; ?> btnUnderstand_vote_up" value="<?php echo ($my_vote == VOTE_UP ? VOTE_NONE : VOTE_UP); ?>" key="<?php echo $replyData['Reply']['id']; ?>"></button>
                        <?php } ?>
                        <p class="txtPoints btnUnderstandCount"><?php echo $ranking; ?></p>
                        <button class="btnUnderstand btnUnderstand_vote_down" disabled></button>
                    </div>
                    <div class="boxItemContentDetail question-preview markdown-area">
                        <p><?php echo $summary_text; ?></p>
                    </div>
                    <div class="writerSts clearfix">
                        <?php if ($replyData['Reply']['user_id'] == $User['id']) { ?>
                        <div class="boxPostscript floatL">
                            <p class="txtPostscript floatL">
                                <a href="/questions/editreply/<?php echo $replyData['Reply']['id'];?>"><button class="btnEditQuestion mod-btn mod-btnWhite">Thay đổi câu trả lời</button></a>
                            </p>
                        </div>
                        <?php } ?>
                        <div class="boxTime">
                            <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['created']));?>　Đăng bài</p>
<?php if ($replyData['Reply']['created'] != $replyData['Reply']['modified']): ?>
                            <p class="txtUpdateTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['modified']));?>　Thay đổi</p>
<?php endif; ?>
                        </div>
                        <div class="boxStat floatR">
                            <div class="boxStatThumb clearfix">
                                <p class="boxRadius_48">
<?php if (FLAG_NOT_DELETED === $replyUserData['delete_flag']): ?>
                                    <a href="/users/<?php echo $replyUserData['display_name'];?>">
<?php endif; ?>
                                    <?php echo $this->Html->image($img_src, array('alt' => __($replyUserData['display_name']), 'class'=>'icnUserThumb_22'));?>
<?php if (FLAG_NOT_DELETED === $replyUserData['delete_flag']): ?>
                                    </a>
<?php endif; ?>
                                </p>
                            </div>
                            <p class="txtUserName">
<?php if (FLAG_NOT_DELETED === $replyUserData['delete_flag']): ?>
                                <a href="/users/<?php echo $replyUserData['display_name'];?>">
<?php endif; ?>
                                    <?php echo $replyUserData['display_name'];?>
<?php if (FLAG_NOT_DELETED === $replyUserData['delete_flag']): ?>
                                </a>
<?php endif; ?>
                                <span class="txtUserPoint">score <?php echo (isset($replyUserData["UserVoteScore"]["total"]))? $replyUserData["UserVoteScore"]["total"] : ""; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="boxEditComment question-editor-area boxPost clearfix">
                            <div class="boxAnswerCommentList" id="rc483">
                                <?php
                                if(isset($replyData['Comments']["total_count"]) && $replyData['Comments']["total_count"] > 0){
                                ?>
                                <p class="ttlComment">Bình luận (<?php echo isset($replyData['Comments']["total_count"]) ? $replyData['Comments']["total_count"] : 0; ?>)</p>
                                <?php
                                }
                                ?>
                                <p class="ttlAnswerComment" disabled="">
                                    <a href="#CommentInfoFormR<?php echo $replyData['Reply']['id']; ?>" id="js-textareaToggle" class="btnComment">Đăng bình luận</a>
                                </p>
                                <div class="boxAnswerComment clearfix">
                                    <?php
                                    if(isset($replyData['Comments']['list'])) {
                                    $show_count = 0;
                                    foreach ($replyData['Comments']['list'] as $commentData) {
                                    $img_src_for_comment = (!is_file(IMAGES.USER_AVATAR_DIR.$commentData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$commentData['User']['photo'];
                                    ?>
                                    <div class="boxCommentContent arrow_box_b markdown-area question-preview clearfix">
                                        <div class="boxStat clearFix">
                                            <div class="boxUser floatL">
<?php if (FLAG_NOT_DELETED === $commentData['User']['delete_flag']): ?>
                                                <a href="/users/<?php echo $commentData['User']['display_name'];?>">
<?php else: ?>
                                                <a nohref="nohref">
<?php endif; ?>
                                                    <p class="boxRadius_22">
                                                        <?php echo $this->Html->image($img_src_for_comment, array('alt' => __($commentData['User']['display_name']), 'class'=>'icnUserThumb_22'));?>
                                                    </p>
                                                    <p class="txtUserName"><?php echo $commentData['User']['display_name'];?></p>
                                                </a>
                                            </div>
                                            <div class="boxStatThumb floatL">
                                                <p class="commentUpdate"><?php echo date('Y/m/d H:i', strtotime($commentData['created']));?></p>
                                            </div>
                                        </div>
                                        <p class="boxItemContentDetail question-preview">
                                            <?php echo nl2br(h($commentData['comment_str'])); ?>
                                        </p>
                                    </div>
                                    <?php if($isAdministrator) {?>
                                    <p class="txtPostscript floatL">
                                       <?php echo $this->Html->link('Xóa bình luận cho câu trả lời',array('controller' => 'questions', 'action' => 'deletereplycomment', $commentData['id']));?>&nbsp;&nbsp;
                                    </p>
                                    <p class="txtPostscript floatL">
                                       <?php echo $this->Html->link('Thay đổi bình luận cho câu trả lời',array('controller' => 'questions', 'action' => 'editreplycomment', $commentData['id']));?>
                                    </p>
                                    <?php } ?>
                                    <?php
                                    }}
                                    ?>
                                    <?php
                                    echo $this->Element('Frontend/question/comment', array(
                                    'type' => 0, 'id' => $replyData['Reply']['id'], 'placeholder' => 'Hãy nhập bình luận'));
                                    ?>
                                    <?php /*
                                    <p class="commentTextArea" id="fComment001">

                                        <textarea id="" rows="4" cols="30" class="" placeholder="あなたのコメント" name="data[Reply][body]" style="resize: none;"></textarea>
                                    </p>
                                    <p class="btnSubmit">
                                        <input type="hidden" value="0" class="comment_type">
                                        <a href="#" class="submit-comment btnSend">送信する</a></p>
                                    */ ?>
                                </div>
                            </div>
                        </div>
                    <?php if($isAdministrator) {?>
                    <ul>
                        <li class="bkgComment floatL"><?php echo $this->Html->link('Thay đổi trả lời',
                                       array('controller' => 'questions', 'action' => 'editreply', $replyData['Reply']['id']));?></li>
                        <li class="bkgComment floatL"><?php echo $this->Html->link('Xóa trả lời',
                                       array('controller' => 'questions', 'action' => 'deletereply', $replyData['Reply']['id']));?></li>
                    </ul>
                    <?php } ?>
                </section>
            </div>

<?php } ?>
<?php if($key == 0 ) { ?>
<?php if ($replyData['Reply']['best_answer_flag'] == 1) { ?>
      <!------------▼その他アンサー start ------------>
  <?php if (count($replys) > 1) { ?>
      <div id="boxOtherAnswer">
  <?php } ?>
<?php } else { ?>
      <!------------▼その他アンサー start ------------>
      <div id="boxOtherAnswer">
<?php } ?>


<?php } ?>
<?php if($replyData['Reply']['best_answer_flag'] != 1) { ?>




                <section class="boxItem">
                    <div class="btnUnderstandWrap">
                        <?php $bottonClass = ($my_vote == VOTE_UP || $User['id'] == $replyData['Reply']['user_id']) ? "btnUnderstand_on" : "btnUnderstand"; ?>
                        <?php $class = (isset($user_id) && !empty($user_id)) ? ' btnUnderstand01'  : ' btnModalLogin'; ?>
                        <button class="<?php echo $bottonClass.$class; ?> btnUnderstand_vote_up" value="<?php echo (($my_vote == VOTE_UP || $User['id'] == $replyData['Reply']['user_id']) ? VOTE_NONE : VOTE_UP); ?>" key="<?php echo $replyData['Reply']['id']; ?>"></button>
                        <p class="txtPoints btnUnderstandCount"><?php echo $ranking; ?></p>
                        <button class="btnUnderstand btnUnderstand_vote_down" disabled></button>
                    </div>
                    <div class="boxItemContentDetail question-preview markdown-area">
                        <p><?php echo $summary_text; ?></p>
                    </div>
<?php if($isAdministrator) {?>
<ul>
                   <li class="bkgComment floatL"><?php echo $this->Html->link('Thay đổi câu trả lời',
                                   array('controller' => 'questions', 'action' => 'editreply', $replyData['Reply']['id']));?></li>
                   <li class="bkgComment floatL"><?php echo $this->Html->link('Xóa câu trả lời',
                                   array('controller' => 'questions', 'action' => 'deletereply', $replyData['Reply']['id']));?></li>
</ul>
<?php } ?>
                    <div class="writerSts clearFix">
                        <?php if ($replyData['Reply']['user_id'] == $User['id']) { ?>
                        <div class="boxPostscript floatL">
                            <p class="txtPostscript floatL">
                                <a href="/questions/editreply/<?php echo $replyData['Reply']['id'];?>">
                                    <button class="btnEditQuestion mod-btn mod-btnWhite">Thay đổi câu trả lời</button>
                                </a>
                            </p>
                        </div>
                        <?php } ?>
                        <?php if (($is_owner || $isAdministrator) && $questionData['Question']['accepted_flag'] != REPLY_BEST_ANSWER) { ?>
                        <span class="btnBestAnswer floatL">
                        <?php echo $this->Html->link('Câu trả lời đúng nhất',array(
                        'controller' => 'Questions',
                        'action' => 'updateBestAnswer',
                        $questionData['Question']['id'],
                        $replyData['Reply']['id'],
                        REPLY_BEST_ANSWER
                        ),
                        array('class' => 'mod-btn mod-btnRed')
                        );?></span>
                        <?php } ?>
                        <div class="boxTime">
                            <p class="txtPostTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['created']));?>　Đăng bài</p>
<?php if ($replyData['Reply']['created'] != $replyData['Reply']['modified']): ?>
                            <p class="txtUpdateTime"><?php echo date('Y/m/d H:i', strtotime($replyData['Reply']['modified']));?>　Thay đổi</p>
<?php endif; ?>
                        </div>
                        <div class="boxStat floatR">
                            <div class="boxStatThumb clearFix">
                                <p class="boxRadius_48">
                                    <?php if ($replyUserData['delete_flag'] === 0):?>
                                    <a href="/users/<?php echo h($replyUserData['display_name']);?>">
                                    <?php echo $this->Html->image($img_src, array('alt' => __($replyUserData['display_name']), 'class'=>'icnUserThumb_22'));?>
                                    </a>
                                    <?php else: ?>
                                    <?php echo $this->Html->image($img_src, array('alt' => __($replyUserData['display_name']), 'class'=>'icnUserThumb_22'));?>
                                    <?php endif; ?>
                                </p>

                            </div>
                            <p class="txtUserName">
                                <?php if ($replyUserData['delete_flag'] === 0):?>
                                <a href="/users/<?php echo h($replyUserData['display_name']);?>">
                                <?php echo h($replyUserData['display_name']);?>
                                </a>
                                <?php else: ?>
                                <?php echo h($replyUserData['display_name']);?>
                                <?php endif; ?>
                                <span class="txtUserPoint">score <?php echo $replyUserData["UserVoteScore"]["total"]; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="boxEditComment question-editor-area boxPost clearfix">
                        <div class="boxAnswerCommentList" id="rc483">
                            <?php
                            if(isset($replyData['Comments']["total_count"]) && $replyData['Comments']["total_count"] > 0){
                            ?>
                            <p class="ttlComment">Bình luận (<?php echo isset($replyData['Comments']["total_count"]) ? $replyData['Comments']["total_count"] : 0; ?>)</p>
                            <?php
                            }
                            ?>
                            <p class="ttlAnswerComment" disabled="">
                                <a href="#CommentInfoFormR<?php echo $replyData['Reply']['id']; ?>" id="js-textareaToggle" class="btnComment">Đăng bình luận</a>
                            </p>
                            <div class="boxAnswerComment clearfix">
                                <?php
                                  $show_count = 0;
                                  if(isset($replyData['Comments']['list']) && !empty($replyData['Comments']['list'])) {
                                  foreach ($replyData['Comments']['list'] as $commentData) {
                                    $img_src_for_comment = (!is_file(IMAGES.USER_AVATAR_DIR.$commentData['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$commentData['User']['photo'];
                                ?>
                                <div class="boxCommentContent arrow_box_b markdown-area question-preview clearfix">
                                    <div class="boxStat clearFix">
                                        <div class="boxUser floatL">
<?php if (FLAG_NOT_DELETED === $commentData['User']['delete_flag']): ?>
                                            <a href="/users/<?php echo $commentData['User']['display_name'];?>">
<?php else: ?>
                                                <a nohref="nohref">
<?php endif; ?>
                                                <p class="boxRadius_22">
                                                <?php echo $this->Html->image($img_src_for_comment, array('alt' => __($commentData['User']['display_name']), 'class'=>'icnUserThumb_22'));?>
                                                </p>
                                                <p class="txtUserName"><?php echo $commentData['User']['display_name'];?></p>
                                            </a>
                                        </div>
                                        <div class="boxStatThumb floatL">
                                            <p class="commentUpdate"><?php echo date('Y/m/d H:i', strtotime($commentData['created']));?></p>
                                        </div>
                                    </div>
                                    <p class="boxItemContentDetail question-preview">
                                        <?php echo nl2br(h($commentData['comment_str'])); ?>
                                    </p>
                                </div>
                                <?php if($isAdministrator) {?>
                                <p class="txtPostscript floatL"><?php echo $this->Html->link('Xóa bình luận câu trả lời',
                                array('controller' => 'questions', 'action' => 'deletereplycomment', $commentData['id']));?>&nbsp;&nbsp;</p>
                                <p class="txtPostscript floatL"><?php echo $this->Html->link('Thay đổi bình luận câu trả lời',
                                array('controller' => 'questions', 'action' => 'editreplycomment', $commentData['id']));?></p>
                                <?php } ?>
                                <?php
                                  }}
                                ?>
                                <?php
                                echo $this->Element('Frontend/question/comment', array(
                                'type' => 0, 'id' => $replyData['Reply']['id'], 'placeholder' => 'Hãy nhập bình luận'));
                                ?>
                                <?php /*
                                <!--
                                <p class="commentTextArea" id="fComment001">

                                    <textarea id="" rows="4" cols="30" class="" placeholder="あなたのコメント" name="data[Reply][body]" style="resize: none;"></textarea>
                                </p>
                                <p class="btnSubmit">
                                    <input type="hidden" value="0" class="comment_type">
                                    <a href="#" class="submit-comment btnSend">送信する</a></p>
                                -->
                                */ ?>
                            </div>
                        </div>
                    </div>
               </section>




    <?php } ?>
    <?php } ?>
<?php if ($replyData['Reply']['best_answer_flag'] != 1 || count($replys) != 1) { ?>
  </div>
<?php } ?>

<?php endif; ?>

            </div>

<?php if(isset($user_id) && !empty($user_id) && !$is_owner) { ?>
    <h2 class="mod-ttl">Câu trả lời của bạn</h2>
    <section class="boxYourAnswer boxPost">
      <?php echo $this->Element('Frontend/forms/comment',array('comment_flg' => 0, 'placeholder' => 'Hãy nhập nội dung trả lời')); ?>
    </section>
<?php } ?>

<?php if(!(isset($user_id) && !empty($user_id))) { ?>
<p class="boxAnswerNotLogin">
    <button class="btnModalLogin btnAnswerNotLogin mod-btn mod-btnBlue">Đăng nhập / Tạo tài khoản mới để trả lời</button>
</p>
<?php } ?>

<h2 class="mod-ttl">Câu hỏi liên quan</h2>
<div class="boxContentWrap boxRelevanceQuestions">
  <ul>
<?php
if (!empty($suggestedQuestionsBottom)) {
    foreach ($suggestedQuestionsBottom as $questionData) {
        echo $this->Element('Frontend/question/list_related', array('questionData' => $questionData));
    }
}
?>
  </ul>
</div>

<h2 class="mod-ttl">Xem những câu hỏi có cùng tag</h2>
<div class="boxTag clearFix boxRelevanceTags">
    <ul>
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
                'val' => $question_tag['Tag']['name']
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
</div>
</div>
  <!-------------------- Side start ------------------- --->
  <div id="sideContainer">
    <!---- ▼関連Q&A一覧 start ---->
    <section class="boxRelated">
      <h2 class="ttlSub ttlRelated ttlSub_bkgBk">Câu hỏi liên quan</h2>
      <ul id="relatedScroll">
<?php
if (!empty($suggestedQuestions)) {
  foreach ($suggestedQuestions as $questionData) {

    $page_view = isset($questionData['QuestionPvCount']['pv_counter']) ? $questionData['QuestionPvCount']['pv_counter'] + 0 : 0;
    $title_text = $this->String->getShortText(h($questionData['Question']['title']), QUESTION_SUMMARY_TITLE_LENGTH);
    $relative_modified_date = $this->String->displayPostTime($questionData['Question']['modified']);
    $summary_text = $this->String->getShortText(Markdown(h($questionData['Question']['body'])), QUESTION_SUMMARY_BODY_LENGTH);

    $replyClass = ($questionData['reply_num'] <= 0) ? " txt0Number" : "";
    $clipClass = ($questionData['clip_num'] <= 0) ? " txt0Number" : "";

    $replyNumber = (isset($questionData['reply_num'])) ? $questionData['reply_num'] : 0;
    $clipNumber = (isset($questionData['clip_num'])) ? $questionData['clip_num'] : 0;


?>
        <li>
          <dl>
            <dt class="boxViews">
<?php if ($questionData['Question']['accepted_flag'] == REPLY_BEST_ANSWER) { ?>
                <dt class="boxViews"> <span class="txtResolution">Xong</span>
<?php } else { ?>
                <span class="txtAccepting">Đợi</span>
<?php } ?>
                <div>
                  <p class="txtViwesAnswer<?php echo $replyClass;?>">Trả lời:<span><?php echo $replyNumber; ?></span></p>
                  <p> / </p>
                  <p class="txtViwesClip<?php echo $clipClass;?>">Clip:<span><?php echo $clipNumber; ?></span></p>
                </div>
            </dt>
            <dd class="txtContent">
              <p><a href="/questions/<?php echo $questionData['Question']['id'] ?>"><?php echo $title_text;?></a></p>
            </dd>
          </dl>
        </li>
<?php
  }
}
?>
      </ul>
    </section>
<?php //echo $this->Element('Frontend/sidebar' , array('tags' => array()) ); ?>
  </div>
<div class="boxShareFixed">
    <p  class="btnShareTwitter">
        <a href="https://twitter.com/share?count=horizontal&original_referer=<?php echo $fullPath; ?>?ip=n0070000_019&text=<?php echo strip_tags($title_for_layout) ?>&url=<?php echo $fullPath; ?>?ip=n0070000_019&hashtags=teratail" onclick="window.open(this.href, 'tweetwindow', 'width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;">
            <img src="/img/questions/detail/imgShareTwitter.png" />
        </a>
    </p>
    <p class="btnShareFacebook">
        <a href="https://www.facebook.com/share.php?u=<?php echo $fullPath; ?>?ip=n0070000_019" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;">
            <img src="/img/questions/detail/imgShareFacebook.png" />
        </a>
    </p>
    <p class="btnShareGoogle">
        <a href="https://plus.google.com/share?url=<?php echo $fullPath; ?>?ip=n0070000_019" onclick="window.open(this.href, 'Gwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;">
            <img src="/img/questions/detail/imgShareGoogle.png" />
        </a>
    </p>
</div>
<div id="form-callback"> </div>
</div>

<script type="text/javascript">
  window.___gcfg = {lang: 'ja'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


<script type="text/javascript">
  function goToByScroll(id) {
    // Remove "link" from the ID
    id = id.replace("link", "");
    // Scroll
    $('html,body').animate({
      scrollTop: $("#" + id).offset().top},
    'slow');
  }
  $(document).ready(function() {
    //$(window).scrollTop(0);
    if ($('.help-inline').length > 0) {
      var divPosition = $('#ReplyInfoForm').offset();
      $('html, body').animate({scrollTop: divPosition.top}, "slow");
    }
});
</script>

<?php $this->start('body_id'); ?>pageID_qadetail<?php $this->end();?>

<form action="javascript:;">
  <input type="file" id="file" style="opacity:0;width:0px;height:0px;font-size:0" accept="image/*" />
</form>

<!-- highlight -->
<?php
echo $this->Html->script(array(
    'plugins/highlight.pack',
));
?>
<script>hljs.configure({useBR: false});hljs.initHighlightingOnLoad();</script>

<?php
echo $this->Html->script(array(
    'frontend/question_editor',
    'plugins/bootstrap-markdown',
    'plugins/bootstrap-tagsinput',
    'plugins/jquery.autoSave.min',
));
?>

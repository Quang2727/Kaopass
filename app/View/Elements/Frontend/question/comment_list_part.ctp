<?php
$class = 'boxAnswerComment';
if(isset($comment_type) && $comment_type == 1) {
    $class = 'boxCommentPostScript';
}
?>
<?php
    if (!empty($comment_data['User'])) {
      $medal_list = array(
        'question_badge' => isset($comment_data["UserCount"][6])?$comment_data["UserCount"][6]:0,
        'reply_badge' => isset($comment_data["UserCount"][7])?$comment_data["UserCount"][7]:0,
        'other_action_badge' => isset($comment_data["UserCount"][8])?$comment_data["UserCount"][8]:0,
      );
      $point_for_comment = isset($comment_data["UserCount"][5])?$comment_data["UserCount"][5]:0;
    }
    $img_src_for_comment = (!is_file(IMAGES.USER_AVATAR_DIR.$comment_data['User']['photo'])) ? 'users/setting/icnUserSample.jpg' : USER_AVATAR_DIR.$comment_data['User']['photo'];

    $isAdministrator = (!empty($User) && $User['user_type'] == 1) ? true : false;        
?>

<div class="<?php echo $class; ?>">
    <div class="boxCommentContent arrow_box_b markdown-area question-preview">
        <p class="boxItemContentDetail question-preview"><?php echo $comment_data["comment_str"]; ?></p>
    </div>
    <dl class="boxStat clearFix">
        <dt class="boxStatThumb floatL">
            <p class="boxRadius_22"> <img class="icnUserThumb_22" alt="Ảnh đại diện" src="<?php echo $img_src_for_comment; ?>"> </p>
        </dt>
        <dd class="boxUser floatL">
            <?php if (!empty($comment_data['User'])) { ?>            
                <p class="txtUserName floatL"> <a href="/users/<?php echo $comment_data["User"]["id"]; ?>"><?php echo $comment_data["User"]["display_name"]; ?></a> </p>
                <p class="boxUserPoint floatL"> <span class="txtUserPoint"><?php echo $point_for_comment; ?>pt</span> <img alt="Huy hiệu vàng" src="/img/common/icnBatch_gold.png"><?php echo $medal_list["question_badge"]; ?><img alt="Huy hiệu bạc" src="/img/common/icnBatch_silver.png"><?php echo $medal_list["reply_badge"]; ?><img alt="Huy hiệu đồng" src="/img/common/icnBatch_copper.png"><?php echo $medal_list["other_action_badge"]; ?></p>
            <?php } ?>
        </dd>
    </dl>
 <?php if($isAdministrator) {?>
                   <p class="txtPostscript floatL"><?php echo $this->Html->link('Xoá bình luận của câu hỏi',
                           array('controller' => 'questions', 'action' => 'deletequestioncomment', $comment_data['id']));?>&nbsp;&nbsp;</p>
                   <p class="txtPostscript floatL"><?php echo $this->Html->link('Thay đổi bình luận của câu hỏi',
                                   array('controller' => 'questions', 'action' => 'editquestioncomment', $comment_data['id']));?></p>                   
 <?php } ?>                             
</div>
<script type='text/javascript'>
// highlight
$(function() {
    hljs.configure({useBR: true});
    $('pre code').each(function(i, e) {
        hljs.highlightBlock(e);
    });
})
</script>

<!-------------------- Side start ---------------------->
<div id="sideContainer" class="l-sideContent">
<?php
if(isset($User)) {
    echo $this->Element('Frontend/sidebar/my_tags', array(
        'myTags' => $myTags,
    ));
}
?>

<?php //アクティブユーザ
echo $this->Element('Frontend/sidebar/active_users', array(
    'userList' => $users,
    'myFollowList' => $follows,
));
?>

<?php //関連タグ
echo $this->Element('Frontend/sidebar/related_tags', array(
    'tagList' => $tags,
    'myTagList' => $userTags,
));
?>

</div>

<?php
//  echo $this->Html->css(array(
//     'tags/style'
// ));

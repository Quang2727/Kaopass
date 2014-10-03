<h1 class="ttlMain"><span class="txtUserName">Danh sách huy hiệu của <?php echo h($display_name); ?></span></h1>
    <!------------▼boxFilterWrap「all」 start ------------>
    <section class="boxFilterWrap">
        <h2 class="ttlSub ttlOtherAnswer">Huy hiệu dành cho đáp án</h2> 
        <ul class="boxBadgeList clearfix">
<?php foreach($badges['reply_badge'] as $badge) { ?>
    <?php 
    $is_badge = false;
    foreach($user_badges['reply_badge'] as $user_badge) { ?>
        <?php if($badge['Medal']['id'] == $user_badge['UserMedal']['medal_id']) {?>
<?php
    $rule_name = $badge['Medal']['rule_name'];
    $comment = $badge['Medal']['comment'];        
?>
<li class="tooltip" title="<?php echo $comment; ?>">
            <span class="icnBage"><?php
echo $this->Html->image($badge['Medal']['image'],
    array(
        "alt" => "Huy hiệu",
        "width" => "70",
        "height" => "70"
    )
);
?></span>
            <span class="txtBageName"><?php echo $badge['Medal']['name']; ?></span>
</li>
        <?php
        $is_badge = true;        
        break;
        }
    }
    if(!$is_badge) {?>
<li>
            <span class="icnBage">
<?php
echo $this->Html->image('badges/icnBadge_unknown.png',
    array(
        "alt" => "Huy hiệu"
    )
);
?>
            </span>
            <span class="txtBageName">? ? ?</span>
</li>

    <?php } ?>
<?php } ?>

        </ul>
        <h2 class="ttlSub ttlOtherAnswer">Huy hiệu dành cho câu hỏi</h2> 
        <ul class="boxBadgeList clearfix">
<?php foreach($badges['question_badge'] as $badge) { ?>
    <?php 
    $is_badge = false;
    foreach($user_badges['question_badge'] as $user_badge) { ?>
        <?php if($badge['Medal']['id'] == $user_badge['UserMedal']['medal_id']) {?>
<?php
    $rule_name = $badge['Medal']['rule_name'];
    $comment = $badge['Medal']['comment'];        
?>
<li class="tooltip" title="<?php echo $comment; ?>">
            <span class="icnBage"><?php
echo $this->Html->image($badge['Medal']['image'],
    array(
        "alt" => "Huy hiệu",
        "width" => "70",
        "height" => "70"
    )
);
?></span>
            <span class="txtBageName"><?php echo $badge['Medal']['name']; ?></span>
</li>
        <?php
        $is_badge = true;        
        break;
        }
    }
    if(!$is_badge) {?>
<li>
            <span class="icnBage">
<?php
echo $this->Html->image('badges/icnBadge_unknown.png',
    array(
        "alt" => "Huy hiệu"
    )
);
?>                
            </span>
            <span class="txtBageName">? ? ?</span>
</li>

    <?php } ?>
<?php } ?>
        </ul>
        <h2 class="ttlSub ttlOtherAnswer">Huy hiệu dành cho hành động khác</h2>   
        <ul class="boxBadgeList clearfix">
<?php foreach($badges['other_action_badge'] as $badge) { ?>
    <?php 
    $is_badge = false;
    foreach($user_badges['other_action_badge'] as $user_badge) { ?>
        <?php if($badge['Medal']['id'] == $user_badge['UserMedal']['medal_id']) {?>
<?php
    $rule_name = $badge['Medal']['rule_name'];
    $comment = $badge['Medal']['comment'];
?>
<li class="tooltip" title="<?php echo $comment; ?>">
            <span class="icnBage"><?php
echo $this->Html->image($badge['Medal']['image'],
    array(
        "alt" => "Huy hiệu",
        "width" => "70",
        "height" => "70"
    )
);
?></span>
            <span class="txtBageName"><?php echo $badge['Medal']['name']; ?></span>
</li>
        <?php
        $is_badge = true;        
        break;
        }
    }
    if(!$is_badge) {?>
<li>
            <span class="icnBage">
<?php
echo $this->Html->image('badges/icnBadge_unknown.png',
    array(
        "alt" => "Huy hiệu"
    )
);
?>
            </span>
            <span class="txtBageName">? ? ?</span>
</li>

    <?php } ?>
<?php } ?>
        </ul>
    </section>

<?php $this->start('body_id'); ?>pageID_badgelist<?php $this->end();?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li><a href="/users/<?php echo h($display_name); ?>"><?php echo h($display_name); ?></a></li>
<li>Danh sách huy hiệu</li>
<?php $this->end();?>

<?php
echo $this->Html->css(array(
    'badges/badges',
    'tooltipster'
));
echo $this->Html->script(array(
    'jquery.tooltipster',
));
?>

    <script type="text/javascript">
 /* ---------------- 絞り込み切り替え ---------------- */
$(function() {
$("#tabFilter li").click(function() {
var num = $("#tabFilter li").index(this);
$(".boxFilterWrap").hide();
$(".boxFilterWrap").eq(num).fadeIn();
$(".headPager").hide();
$(".headPager").eq(num).fadeIn();
$("#tabFilter li").removeClass('on');
$(this).addClass('on')
});
});
</script> 
    <script type="text/javascript">
 /* ---------------- ツールチップ ---------------- */
$(function(){
$('.tooltip').tooltipster({
    arrow: true,    
    arrowColor: '', 
    delay: 200,
    fixedWidth: 300,
    followMouse: true,  
    offsetX: 0, 
    offsetY: -60,           
    overrideText: '',       
    position: 'bottom',
    speed: 500, 
});
});
</script> 

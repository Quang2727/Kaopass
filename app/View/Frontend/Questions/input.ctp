<script type="text/javascript">CONTRIBUTED_IMAGES_DIR = <?php echo "'".$image_path."'"; ?></script>
<?php
$model = (!empty($model)) ? $model :'Question';
$textareaField = 'comment';
if($model == 'Question' || $model == 'Reply') {
    $textareaField = 'body';
}
if (isset($editQuestion) === false) $editQuestion = null;

$this->Html->css(array(
  'font-awesome.min',
  'questions/markdown',
  'plugins/bootstrap-markdown.min',
  'highlight/default',
  'highlight/highlight',
  'questions/questions',
  'questions/input',
),
   null, array(
     'inline' => false));
echo $this->Html->script(array(
    'plugins/bootstrap-markdown',
    'plugins/bootstrap-tagsinput',
    'plugins/jquery.autoSave.min',
    'frontend/question_editor',
),
   array(
     'inline' => false));

$this->assign('body_id', "pageID_question");
?>
<?php $this->start('breadcrumb');?>
<?php if($model == 'Question' && $editQuestion != "edit") {?>
<li>Đặt câu hỏi</li>
<?php } elseif ($model != 'Question') {?>
<li>Thay đổi trả lời</li>
<?php } else {?>
<li>Thay đổi câu hỏi</li>
<?php } ?>
<?php $this->end();?>

<div id="mainContainer">
<div class="clearfix">
  <h1 class="ttlMain">
<?php echo $title_for_layout; ?>
  </h1>
</div>
<?php if(!empty($errors)) { ?>
<div class="boxError">
    <?php foreach($errors as $error) { ?>
  <p class="txtError"><?php echo$error[0]; ?></p>
    <?php } ?>
</div>
<?php } ?>

<!------------▼質問する start ------------>
<section class="boxPost">
<?php echo $this->Form->create($model); ?>
<?php
    echo $this->Form->hidden($model . '.id');
    echo $this->Form->hidden('draft_type', array('value' => $model));
    if (!empty($editQuestion))
        echo $this->Form->hidden('edit', array('value' => $editQuestion));
    if ($model == 'Question' && !empty($question['Question']['id']))
        echo $this->Form->hidden('question_id', array('value' => $question['Question']['id']));
?>

<dl class="question-editor-area">
<?php if ($model == 'Question') { ?>
<dt>Tiêu đề câu hỏi<span>(nội dung không quá 100 ký tự)</span></dt>
<dd>
<?php
echo $this->Form->input(
    $model . '.title', array(
        'type' => 'text',
        'class' => 'inputTitle',
        'maxlength' => 100,
        'label' => false,
        'data-rule-id' => 'title',
        'placeholder' => 'Hãy viết rõ vấn đề của câu hỏi nhé',
        'error' => false,
    )
);
?>
</dd>

<dt>Tag liên quan<span>(tối đa 5 tag)</span><a href="/contact/input" class="txtLinkNewTag" target="_blank">Hãy liên hệ tại đây nếu bạn muốn thêm tag mới</a></dt>
<dd class="boxLabelInput">
<div id="js-undecidedBox" class="clearfix"></div>
<?php
echo $this->Form->input(
    $model . '.tags', array(
        'type' => 'text',
        'class' => 'w30 input-block-level',
        'id' => 'tags-input',
        'maxlength' => 100,
        'label' => true,
        'required' => false,
        'data-rule-id' => 'tag',
        'div' => false,
        'placeholder' => 'Hãy thêm tag cho câu hỏi',
        'error' => false,
    )
);
?>
<?php } ?>
    
<?php if ($model == 'Question') { ?>
<dt>Nội dung câu hỏi
<span>（giới hạn từ 50 ~ 10000 ký tự）</span></dt>
<?php } else { ?>
<dt>本文</dt>
<?php } ?>
<dd class="txtEdit">
<?php
echo $this->Form->input(
    $model . ".$textareaField", array(
        'type' => 'textarea',
        'class' => 'question-editor input-block-level',
        'rows' => 10,
        'label' => false,
        'data-rule-id' => 'body',
        'placeholder' => 'Hãy viết nội dung câu hỏi một cách cụ thể nhất để có câu trả lời tốt nhất. Và hãy sử dụng công cụ "code" để source code và nội dung được phân biệt rõ ràng hơn.',
        'div' => false,
        'error' => false,
    )
);
?>
</dd>
  <dd class="txtBodyPreview">
    <div class="controls">
        <p id="question-preview" class="question-preview preview-selector boxItemContentDetail">Xem trước</p>
    </div>
  </dd>
</dl>

<p class="btnSubmit" id="btnModalOpen">
<?php

if($model == 'Question' && $editQuestion != "edit") {
    $sendButton = 'Đặt câu hỏi';
} else {
    $sendButton = 'Thay đổi';
}
echo $this->Form->button(
    $sendButton, array(
        'type' => 'submit',
        'class' => 'mod-btn mod-btnQuestion mod-btnSubmit l-btnSignup-center',
        'id' => 'submit_question',
        'div' => false,
    )
);
?>
</p>

<?php if($model == 'Question') {?>
              <div class="boxSNSPost clearfix">
                <p class="txtSNSPost floatL">Đăng đồng thời lên mạng xã hội</p>
                <div class="floatL boxCheck">
                    <input type="checkbox" name="data[Question][social][]" value="Facebook" id="PostFacebookFlug">
                    <label for="PostFacebookFlug">Đăng lên Facebook</label>
                </div>
                <div class="floatL boxCheck">
                    <input type="checkbox" name="data[Question][social][]" value="Twitter" id="PostTwitterFlug">
                    <label for="PostTwitterFlug">Đăng lên Twitter</label>
                </div>
                <p class="txtSNSPostPreview">Nội dung sẽ được đăng theo cấu trúc dưới đây</p>
                <div class="boxSNSPostPreview">
                  <dt>
                      <dd class="PostTitle">Câu hỏi này đã được đăng tại teratail</dd>
                      <dd class="PostTitle">"Tiêu đề câu hỏi sẽ hiển thị tại đây"</dd>
                      <dd class="PostTitle">https://vn.teratail.com/questions/(ID của câu hỏi)</dd>
                      <dd class="PostTitle">#teratail</dd>
                  </dt>
                </div>
              </div>
<?php } ?>

<?php echo $this->Form->end(); ?>
<form action="javascript:;">
  <input type="file" id="file" style="opacity:0;width:0px;height:0px;font-size:0" accept="image/*"/>
</form>


</section>
<!------------ 質問する end ------------>


</div>
<!-------------------- Main end ---------------------->
<!-------------------- Side start ---------------------->
<div id="sideContainer" class="l-sideContent">
<?php if($model == 'Question') { ?>
<!---- ▼投稿ルール start ---->
        <section class="boxPostRule">
            <h2 class="ttlSub">Lưu ý khi đặt câu hỏi</h2>
            <ul>
                <li>Để giải quyết nhanh, trước khi đặt câu hỏi cần tìm hiểu thông tin hoặc câu trả lời liên quan vấn đề bằng cách <span class="txtBold"><a href="#" class="js-focusSearch">tìm kiếm</a></span></li>
                <li><span class="txtBold">Ghi rõ</span> "vấn đề đang vướng" và "tình trạng hiện tại" để nhận câu trả lời tốt nhất.</li>
                <li>Khi vấn đề của bạn đã được giải quyết, hãy chọn <span class="txtBold">câu trả lời đúng nhất</span> đối với câu trả lời đã giúp bạn giải quyết vấn đề.</li>
            </ul>
        </section>
        <section class="boxScalesList">
            <h2 class="ttlSub">Đăng lên SlideShare</h2>
            <div class="boxScalesListContents">
                <p>Có thể viết <span class="txtBold">［WordPress Shortcode］ (sử dụng trong SlideShare)</span> vào nội dung câu hỏi, vì vậy bạn cũng có thể đăng lại trên SlideShare.</p>
            </div>
        </section>
        <section class="boxScalesList">
            <h2 class="ttlSub">Các cú pháp trong Markdown</h2>
            <div class="boxScalesListContents">
                <p>Tùy thuộc vào việc tận dụng các Markdown dưới đây, người khác sẽ thấy dễ trả lời hơn cho câu hỏi của bạn, từ đó bạn có thể giải quyết được vấn đề nhanh chóng</p>
                <p class="txtBold">**ký tự in đậm**</p>
                <p class="txtBold">__ký tự in nghiêng__</p>
                <p class="txtBold">### Tiêu đề</p>
                <p class="txtBold">[nội dung đường dẫn (URL)](http://)</p>
                <p class="txtBold">![alt][WIDTH:px](đường dẫn của file)</p>
                <p class="txtBold">> ký tự trích dẫn</p>
                <p class="txtBold">` Code `</p>
                <p class="txtBold"> - List</p>
<?php //                <p class="txtBold">0. 番号リスト</p> ?>
            </div>
        </section>

<!-- 投稿ルール end -->
<?php } ?>
</div>
<!-- Side end -->

<?php //echo $this->Element('Frontend/forms/question'); ?>

<!-- highlight -->
<?php
echo $this->Html->script(array(
    'plugins/highlight.pack',
));
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>


<?php
$this->assign('body_id', 'pageID_taglist');
$this->Html->css(
    array(
        'tags/tags',
    ),
    null,
    array('inline' => false)
);
?>
<?php $this->start('description');
echo $description_for_layout;
$this->end();?>
<?php $this->start('keywords');
echo $keyword_for_layout;
$this->end();?>
<?php $this->start('breadcrumb');?>
<li>Danh sách Tag</li>
<?php $this->end();?>

<h1 class="ttlMain">Danh sách Tag</h1>
<ol class="txtTtlBtm">
    <li>Nhấn vào tên Tag để chuyển sang danh sách Q&A của Tag đó. </li>
    <li>Nếu thêm vào My Tag những Tag mà bạn thường tìm kiếm, những câu hỏi mới nhất có gắn Tag đó sẽ được cập nhật thường xuyên tại trang chính của bạn. </li>
    <li>Bạn có thể yêu cầu thêm Tag mới vào bất kỳ thời gian nào, hãy liên hệ với chúng tôi tại URL ở cuối trang.</li>
</ol>
<h2 class="ttlSub">Tag đã đăng ký</h2>
<section class="boxTag clearfix">
  <div class="boxTagDetail">
    <ul class="clearFix">
<?php
$submit_url = 'javascript:void(0)';
if (!empty($this->request->params['ref'])) {
    $submit_url = array(
        'controller' => 'login',
        'action' => 'submit',
        '02'
    );
}
//tag_list
    $disp_tag_list = array();
    $check_list = array();
    $my_list = array();
    foreach ($tag_list as $i => $data) {
      $my_list[] = $data['Tag']['id'];
    }
    foreach ($mytag_data as $i => $data) {
      if (in_array($data['Tag']['id'], $my_list) === true) unset($mytag_data[$i]);
    }
    $disp_tag_list = array_merge($tag_list, $mytag_data);
    foreach ($disp_tag_list as $i => $tag_item) {
?>
      <li data-id="<?php echo $tag_item['Tag']['id']; ?>" class="bkgCate_s<?php if (empty($this->request->params['ref'])) echo ' choose-tag'; ?><?php if (!in_array($tag_item['Tag']['id'], $mytag_list)) echo ' disnon'; ?>">
<?php
        if($tag_item["Tag"]["question_counter"] > 0) {
            echo $this->Html->link(
                $tag_item['Tag']['name'],
                '/tags/'.urlencode($tag_item['Tag']['name'])
            );
        } else {
            echo $this->Html->link(
                $tag_item['Tag']['name'],
                'javascript:void(0);',
                array('class' => 'disabled')
            );
        }
        echo $this->Html->link(
            $this->Html->image('common/btnMyTagDelete.png', array('alt'=> __('close'))),
            $submit_url,
            array(
                'class' => 'btnClose',
                'escape' => false
            )
        );
?>
      </li>
<?php } ?>
    </ul>
  </div>
</section>

<div id="boxContentSearch">
<?php
echo $this->Element('Frontend/forms/search',array("class"=>"searchTags"));
?>
</div>

<section class="boxTagList data-tag clearFix ">
<?php
    echo $this->Element('Frontend/search/tag', array("tag_list" => $tag_list));
?>
</section>
</div>
<script type="text/javascript">
    $(document).ready(function(e) {
        $(".searchTags").val("");
    });
</script>

<div id="bkgModalEntryAfter">
</div>
<div id="boxModalEntryAfter">
<section id="boxLogin">
    <div class="boxInner clearFix">
        <p class="txtLead">Chào mừng đến với teratail!<br>
        Trước hết cùng xem những Q & A quan tâm.</p>
        <div class="boxTagDetail clearfix">
            <div class="boxTagDetailMain floatL">
                <div class="clearfix" style="padding-bottom: 15px;">
                    <p class="txt01 floatL"><span>Đơn giản chỉ cần 1 phút</span></p>
                    <p class="txt02 floatL">Sau khi bạn đăng ký MyTag…</p>
                </div>
                <ul class="boxDetailList">
                    <li>Bạn có thể theo dõi những chủ đề mình quan tâm</li>
                    <li>Và dễ dàng tìm thấy những thành viên phù hợp cùng quan điểm</li>
                </ul>
            </div>
            <p class="btnTagEntry floatL"><span style="display: block; padding: 10px; color: #fff; font-weight: bold; line-height: 1.3; text-align: center; font-size:18px;">Đăng ký tag</span></p>
        </div>
        <button class="btnClose"><img src="/img/common/btnClose.png" alt="Đóng" width="26" height="26"></button>
    </div>
</section>
</div>

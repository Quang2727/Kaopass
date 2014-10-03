<?php if ($this->Html->url(null) == '/' || $isQuestion) { ?>
<!-- <div class="boxBottomAsk">
    <p>Nếu bạn cho chúng tôi biết cụ thể về "tình trạng của bạn" hay "vấn đề bạn đang gặp phải", bạn sẽ nhận được giải đáp trong thời gian nhanh nhất.</p>
    <p class="<?php echo $login_class;?>"><a href="<?php echo $login_url;?>" class="mod-btn mod-btnQuestion l-btnLogin-center">Đặt câu hỏi</a></p>
</div> -->
<?php } else { ?>
<dl class="boxAskSearch0">
    <dt>Câu hỏi của bạn, chưa giải quyết được nữa sao?</dt>
    <dd>
        <p class="txtAskSearch0">Dường như chưa có câu hỏi tương tư, <br>nhưng có lẽ mọi người có thể vẫn muốn biết</p>
        <p class="btnBottomAsk btnModalLogin"><a href="<?php echo $login_url;?>" class="mod-btn mod-btnQuestion l-btnLogin-center">Đặt câu hỏi</a></p>
    </dd>
</dl>
<?php } ?>

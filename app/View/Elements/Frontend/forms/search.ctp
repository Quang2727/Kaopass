<form class="navbar-search-realtime ">
    <?php $display = ($class == 'searchTags') ? "Nhập từ khoá để tìm kiếm tag" :"Tìm thành viên"; ?>
    <div class="boxLabelInput">
    <input type="text" size="15" name="search" class="search-query <?php echo $class; ?> txtSearch" value="<?php isset($searchValue) ? $searchValue : null ?>" id="tagSearch" placeholder="<?php echo $display;?>">
    </div>
    <p class="btnSearch"><img src="/img/common/btnSearch.png" width="12" height="12" alt="Tìm kiếm"></p>
</form>

<script>
    $("input").keypress(function (evt) {
        //Deterime where our character code is coming from within the event
        var charCode = evt.charCode || evt.keyCode;
        if (charCode  == 13) { //Enter key's keycode
            return false;
        }
    });
</script>

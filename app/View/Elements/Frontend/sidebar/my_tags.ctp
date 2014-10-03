<?php
/**
 * @author Mai Nhut Tan
 * @since 2013/09/30
 *
 * @var array $tagList
 * @var array $myTagList
 * @var string $title
 */
if (empty($title)) {
    $title = 'MyTag';
}
if (isset($User)) {
    $login_class="btnDescriptionMyTag";
} else {
    $login_class="btnModalLogin";
}
?>

<?php if(!isset($User)): ?>
<section class="boxMyTag">
    <!-- <p class="ttlSub ttlSub_bkgBk ttlMyTag">Myタグ</p> -->
    <p class="ttlSub ttlSub_bkgBk">MyTag</p>
    <p class="l-sideBox-body">Sau khi login, đăng ký "MyTag" sẽ giúp bạn cập nhật câu hỏi nhanh hơn</p>
</section>
<?php
return;
endif;
?>
<section class="boxMyTag">
    <p class="ttlSub ttlSub_bkgBk ttlMyTag">MyTag
        <span id="js-tagDeleteToggle" class="myTagControl is-editable">Sửa</span>
    </p>
    <ul class="boxMyTagList clearfix noToolTip boxTag l-sideBox-body">
    <input name="data[nameTags]" class="tm-input input-block-level" style="display:none" type="text" id="nameTags"/>
    </ul>
    <div class="formMyTag">
        <form class="form_horizontal margin-none" action="/Tags/addTags" id="editUser" method="post" accept-charset="utf-8" name="mytag">
            <div style="display:none;">
                <input name="_method" value="POST" type="hidden">
            </div>
            <div class="mod-formHorizontal clearfix">
                <div id="js-undecidedBox" class="mod-undecidedBox clearfix">
                    <a href="#" class="a_submit mod-btn mod-btnSubmit mod-btnAdd" id="addTags">Thêm tag này vào MyTag</a>
                </div>
                <input name="data[Tags][tag_input]" class="inputMyTag" id="tags-input-data" size="15" style="display: none;" type="text" placeholder="Nhập tag">
            </div>
            <div class="boxError">
                <p class="tag-error txtError"></p>
            </div>
        </form>
    </div>
</section>

<?php
$this->Html->script(array(
    'plugins/scripts',
    'plugins/tagmanager',
    'plugins/bootstrap-tagsinput',
), array('inline' => false));
?>
<script type="text/javascript">
    var listTag={};
    var ArrayTag=[];
    var ArrayMyTag=[];
    var myTag={};
    var resultTag={};
    $("#addTags").click(function(e) {



        var input = $('.inputMyTag').val();
        if(input=='') {
          $(".tag-error").html('Tag này không tồn tại');
          retrun;
        } else {
            $(".boxMyTagFormList,.txtAttentionMyTag").hide();
        }
        $(".tag-error").empty();
        $('#tags-input-data').tagsinput('removeAll');
        jQuery(".tm-input:eq(0)").tagsManager('empty');
        var result = resultTag;
        myTag = {};
        resultTag = {};
        var str="";
        $.ajax({
            url: BASE + "Tags/addTags",
            data: {
                listTag: result
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                for (var i = 0; i < response.content.length; i++)
                {
                    jQuery(".tm-input:eq(0)").tagsManager('pushTag', response.content[i].name, response.content[i].name_enc);
                    myTag[i + 1] = response.content[i].name;
                    resultTag[response.content[i].name] = 1;  
                    str+='<a class="btn btn-mini btn-primary" title="'+ attr_escape(response.content[i].name) +'" href="'+BASE +"Tags/"+response.content[i].name  +'">'+ html_escape(response.content[i].name) +'</a>';
                }
                if(str=="")
                    str="Hãy nhập tag";
                setDataTag();
                $(".form-tags").addClass("hide");
                addTagFeed();

                // 追加アクセス成功したら未確定ボックスを非表示
                $('#js-undecidedBox').hide();
            },
            error: function() {
            }
        });
        
        function attr_escape(str){
            return html_escape(str).replace(/(['"'])/g, '$1');
        }
        
        function html_escape(str){
            return $('<div />').text(str).html();
        }

        return false;
    });
    $( document ).ready(function() {
        $.ajax({
            url: BASE + "Tags/getListTag",
            type: 'POST',
            data:null,
            async: false,
            dataType: 'json',
            success: function(response) {
                for(var i=0;i<response.dataTags.length;i++)
                {
                    listTag[response.dataTags[i].Tag['id']] = response.dataTags[i].Tag['name'];
                    ArrayTag.push(response.dataTags[i].Tag['name']);
                }
                for(var i=0;i<response.myTags.length;i++)
                {
//        ArrayMyTag.push(listTag[response.myTags[i].Tag['id']]);
                    ArrayMyTag.push(response.myTags[i]);
                    myTag[i+1]=listTag[response.myTags[i].Tag['id']];
                    resultTag[listTag[response.myTags[i].Tag['id']]] = 1;

                }
                loadDefaultTag(); 
            },
            error: function(e) {
            }
        });

        if ($('ul.boxMyTagList li').length == 0) {
                // 非表示の場合の処理
                $(this).find(".myTagEdit").css('display','none');
                $(this).find(".myTagEditEnd").css('display','block');
                // $(".boxMyTagList").addClass('tagDelete');
                $(".boxMyTagList li a span").css('display','block');
                // $(".formMyTag").slideDown();
        }

    });
    function setDataTag()
    {
        $(".tag-user").each(function(i,e){
            if(resultTag[$(this).attr("val")]==1)
                $(this).addClass("btn-success");
            else
                $(this).removeClass("btn-success");
        });
    }
    
    function loadDefaultTag()
    {
        jQuery(".tm-input:eq(0)").tagsManager({
            prefilled: ArrayMyTag,
            typeahead: true,
            typeaheadAjaxSource: null,
            typeaheadSource: ArrayTag,
            blinkBGColor_1: '#FFFF9C',
            blinkBGColor_2: '#CDE69C',
            hiddenTagListName: 'tags'
        });
    }
    
    $('#tags-input-data').tagsinput({
        tagClass: function(item) {
            return 'btn btn-mini btn-primary';
        },
        typeahead: {
            source: function(query) {
                $(".tag-error").empty();
                var url = BASE + 'Tags/typehead/' + query;
                if (localCache.exist(url)) {
                    return localCache.get(url);
                }
                return $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    cache: true,
                    data: {
                        ajax: true
                    },
                    complete: function(jqXHR, textStatus) {
                        if (textStatus == 'success') {
                            localCache.set(url, jqXHR);
                        }
                    }
                });
            }
        },
        freeInput: false
    });
</script>

<!--
<p class="ttlSub ttlSub_bkgBk ttlUserSearch">ユーザーを探す</p>
<section class="boxUserSearch">
    <div class="boxLabelInput clearFix">
            <input id="keywordTag" class="txtSearch floatL" type="text" value="" size="15" name="search">
            <input type="submit" value="検索" class="btnSearch floatL">
    </div>
    <p class="txtLinkUsers">
            <a href="/users/">ユーザーの一覧を見る</a>
    </p>
</section>
-->


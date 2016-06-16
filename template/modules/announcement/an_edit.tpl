
<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?m=announcement&a=an_save">
    <input autocomplete="off"  name="an_id" value="{$data.an_id}" type="hidden" />

    <div class="toolbar">
        <div class="block">
            <label class="">{"标题"|L}：</label>
            <input chinese="true" autocomplete="off"  maxlength="32" class="" name="an_title" type="text" style="width:530px" value="{$data.an_title}" required="true"/>
        </div>
        <br />
        <div class="block">
            <label class="">{"区域"|L}：</label>
            <select value='{$data.an_area}' multiple="TRUE" class="autofix autoeditselect" name="an_area[]"  action="?m=area&a=option" selected="true">
                {"<option value='#'>{'全部'|L}</option>"|isallarea}
            </select>
        </div>
    </div>
    <script charset="utf-8" src="kindeditor/kindeditor-min.js"></script>
    <script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
    <script>
                var editor;
                KindEditor.ready(function (K) {
                editor = K.create('textarea[name="content"]', {
                resizeType: 1,
                        allowPreviewEmoticons: false,
                        allowImageUpload: false,
                        items: [
                                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                                'insertunorderedlist', '|', 'emoticons', 'image', 'link']
                });
                });    </script>
    <div class="content">
        <textarea  maxlength="3000" name="content" class="ckeditor" id="content" style="width: 624px; height: 320px;">{$data.an_content}</textarea>
    </div>
    <div class="buttons mrtop40">
        <a goto='?m=announcement&a=index'  form="form" class="ajaxpost2 button normal" >{"发布"|L}</a>

        {if $data.an_status != '1'}
        <a goto='?m=announcement&a=index' form="form" class="ajaxpost1 button normal">{"保存为草稿"|L}</a>
        {/if}
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>

<script {'type="ready"'}>
            (function () {
            valid();
                    var submitpost = function () {

                    if ($("#form").valid()) {
                    var form = $("a.ajaxpost").attr("form");
                            var goto = $("a.ajaxpost1").attr("goto");
                            var url = $("#form").attr("action");
                            var i = $(".ke-edit-iframe").contents().find(".ke-content");
                            $(".ke-edit-iframe").contents().find(".ke-content");
                            $("#content").val(i[0].innerHTML);
                            var data = $("#form").serialize() + "&an_status=0";
                            $.ajax({
                            url: url,
                                    method: "POST",
                                    dataType: "json",
                                    data: data,
                                    success: function (result) {

                                    if (result.status == 0) {
                                    notice(result.msg, goto);
                                    } else {
                                    notice(result.msg);
                                    }
                                    }
                            });
                    }
                    };
                    $("a.ajaxpost1").bind("click", submitpost);
            })();
            (function () {
            valid();
                    var submitpost = function () {
                    if ($("#form").valid()) {
                    var goto = $("a.ajaxpost2").attr("goto");
                            var url = $("#form").attr("action");
                            var i = $(".ke-edit-iframe").contents().find(".ke-content");
                            $(".ke-edit-iframe").contents().find(".ke-content");
                            $("#content").val(i[0].innerHTML);
                            var data = $("#form").serialize() + "&an_status=1";
                            $.ajax({
                            url: url,
                                    method: "POST",
                                    dataType: "json",
                                    data: data,
                                    success: function (result) {
                                    if (result.status == 0) {
                                    notice(result.msg, goto);
                                    } else {
                                    notice(result.msg);
                                    }
                                    }
                            });
                    }
                    };
                    $("a.ajaxpost2").bind("click", submitpost);
            })();
            //$("body.ke-content").html({$data.an_content});
</script>



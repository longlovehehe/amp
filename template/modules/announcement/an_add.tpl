
<h2 class="title">{"{$title}"|L}</h2>

<form id="form" class="base mrbt10" action="?m=announcement&a=an_save" method="post" >
    <div class="toolbar">
        <div class="block">
            <label class="">{"标题"|L}：</label>
            <input chinese="true" autocomplete="off"   maxlength="32" class="" name="an_title" type="text" style="width:530px" required="true"/>
        </div>

        <br />
        <div class="block">
            <label class="">{"区域"|L}：</label>
            <select multiple="TRUE" value="" class="autofix autoedit" name="an_area[]"  action="?m=area&a=option" selected="true">
                {"<option value='#'>{'全部'|L}</option>"|isallarea}
            </select>
        </div>
        <p class="info_text">{"在选择区域时，可单击单选，按住ctrl多选或按住shift连续选择多项"|L}</p>
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
        });
    </script>
    <div class="content">
        <textarea maxlength="3000" name="content" class="ckeditor" id="content" style="width: 624px;height: 400px"></textarea>
    </div>

    <div class="buttons mrtop40">
        <a goto='?m=announcement&a=index'  form="form" class="ajaxpost2 button normal" >{"发布"|L}</a>
        <a goto='?m=announcement&a=index' form="form" class="ajaxpost1 button normal">{"保存为草稿"|L}</a>
        <a class="goback button">{'取消'|L}</a>
    </div>
</form>
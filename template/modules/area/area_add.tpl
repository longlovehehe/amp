<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?m=area&a=area_save" >
    <div class="block">
        <label class="title">{"区域名称"|L}：</label>
        <input autocomplete="off"  maxlength="10" value="{$data.am_name}" name="am_name" type="text" chinese="true" required="true" class="am_name"/>
    </div>
    <div class="buttons mrtop40">
        <a goto="?m=area&a=index" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
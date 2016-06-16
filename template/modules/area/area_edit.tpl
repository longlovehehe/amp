<h2 class="title">{"编辑区域"|L}</h2>

<form id="form" class="base mrbt10" action="?m=area&a=area_save" method="post">
    <input autocomplete="off"  name="am_id" value="{$data.am_id}" type="hidden" />
    <div class="block">
        <label class="title">{"区域名称"|L}：</label>
        <input autocomplete="off" maxlength="10" value="{$data.am_name}" name="am_name" class="am_name" chinese="true" type="text" required="true" />
    </div>
    <div class="buttons mrtop40">
        <a goto="?m=area&a=index" form="form" class="ajaxpost button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
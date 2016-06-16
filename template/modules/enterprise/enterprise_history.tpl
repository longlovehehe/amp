{strip}
<h2 class="title">{"企业变更记录"|L}</h2>
<div class="toolbar">
    <form action="?m=enterprise&a=enterprise_history_item" id="form" method="post">
        <input autocomplete="off" name="modules" value="enterprise" type="hidden" />
        <input autocomplete="off" name="action" value="enterprise_history_item" type="hidden" />
        <input autocomplete="off" name="page" value="0" type="hidden" />
        <input autocomplete="off" name="eh_e_id" value="{$data.e_id}" type="hidden" />

        <div class="line">
            <label>{"企业名称"|L}：</label>
            <span>{$data.e_name}</span>
        </div><br />
        <div class="line">
            <label>{"操作者"|L}：</label>
            <input autocomplete="off" class="autosend" style="width:100px;" name="eh_do_username" type="text" />
        </div>
        <div class="line">
            <label>{"变更时间"|L}：</label>
            <input autocomplete="off" class="datepicker start" style="width:123px;" name="start" type="text" datatime='true' />
            <span>-</span>
            <input autocomplete="off" class="datepicker end" style="width:123px;" name="end" type="text" datatime="true" />
        </div>
        <!-- <div class="buttons right"> -->
        <div class="line">
            <a form="form" class="button submit"><i class="icon-search"></i>{"查询"|L}</a>
        </div>
    </form>
</div>
<div class="content"></div>
{/strip}
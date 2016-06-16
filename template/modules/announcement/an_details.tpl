<form id="form" action="?m=announcement&a=an_add_data" method="post">
    <input autocomplete="off"  type="hidden" name="an_status" id="status">
    <h2 class="title">{"{$data.an_title}"|L}</h1>
        <div class="toptoolbar">
            <a href="?m=announcement&a=an_edit&an_id={$data.an_id}" class="button orange">{"编辑"|L}</a>
        </div>
        <div style="text-align: center;margin: 10px 0;">
            <label>{"发布时间"|L}：</label><label>{$data.an_time}</label>&nbsp;
            <label>{"作者"|L}：</label><label>{$data.an_user}</label>&nbsp;
            <label>{"面向区域"|L}:</label><label>{$data.an_area|mod_area_name}</label>
        </div>
        <div class="content news" style="text-align: left;">{$data.an_content}</div>
</form>

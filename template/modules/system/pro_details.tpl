<div class='toptoolbar'>
    <a class="goback backgo  _20140919053631965_easyicon_net_32_png animated none">
        <em class='none'>{"返回"|L}</em>
    </a>
    <a class="goback button">{"返回"|L}</a>
</div>
<br />
<br />
<br />
<form id="form" action="?modules=announcement&action=an_add_data" method="post">
    <input autocomplete="off"  type="hidden" name="an_status" id="status" />
    <input autocomplete="off"  name="page" value="0" type="hidden" />
    <h2 class="title">
        {$data.an_title}
    </h2>

    <div style="text-align: center;margin: 10px 0;">
        <label>{"发布时间"|L}：</label><label>{$data.an_time}</label>&nbsp;
        <label>{"作者"|L}：</label><label>{$data.an_user}</label>&nbsp;
        <label>{"面向区域"|L}:</label><label>{$data.an_area|mod_area_name:option}</label>
    </div>
    <div class="content news" style="text-align: left">{$data.an_content}</div>
</form>

{strip}


    <h2 class="title">{"历史记录"|L}</h2>

    <div class="toolbar">
        <form action="?m=enterprise&a=user_history_item" id="form" method="post">
            <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
            <input autocomplete="off"  name="action" value="users_history_item" type="hidden" />
            <input autocomplete="off"  name="page" value="0" type="hidden" />
            <input autocomplete="off"  name="uh_u_number" value="{$data.u_number}" type="hidden" />


            <div class="line">
                <label>{"企业名称"|L}：</label>
                <span>{$data.e_name}</span>
            </div><br />
            <div class="line">
                <label>{"用户ID"|L}：</label>
                <span>{$data.u_number|default:{"无"|L}}</span>
            </div>
            <div class="line">
                <label>{"用户名称"|L}：</label>
                <span>{$data.u_name|default:{"无"|L}}</span>
            </div>
            <div class="line">
                <label>{"部门名称"|L}：</label>
                <span>{$data.ug_name|default:{"无"|L}}</span>
            </div>

            <div class="buttons right none">
                <a form="form" class="button submit"><i class="icon-search"></i>{"查询"|L}</a>
            </div>
        </form>
    </div>
    <div class="content"></div>

{/strip}
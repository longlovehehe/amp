{strip}
<div class="toolbar mactoolbar">
    <a href="?m=enterprise&a=index" class="button ">{"企业管理"|L}</a>
    <a href="?m=enterprise&a=allusers" class="button active">{"用户搜索"|L}</a>
    <a href1="?m=device&a=vcrs" class="button none">{"车辆管理"|L}</a>
</div>
<h2 class="title">{"{$title}"|L}</h2>
<div class="toolbar">
    <form action="?m=enterprise&action=all_user_item" id="form" method="get">
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <input autocomplete="off"  name="num" value="100" type="hidden" />
        <h3 class="title">{"基本属性"|L}</h3>
        <div class="line">
            <label>{"姓名"|L}：</label>
            <input value='{$smarty.request.u_name}' autocomplete="off"  class="autosend" name="u_name" type="text" />
        </div>
        <div class="line">
            <label>{"号码"|L}：</label>
            <input value='{$smarty.request.u_number}' autocomplete="off"  class="autosend" name="u_number" type="text" />
        </div>
        <div class="line">
            <label>{"用户类型"|L}：</label>
            <select name="u_sub_type">
                <option value="">{"全部"|L}</option>
                <option value="1">{"手机用户"|L}</option>
                <option value="2">{"调度台用户"|L}</option>
                <option value="3">{"GVS用户"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"用户分类"|L}：</label>
            <select name="u_attr_type">
                <option value="">{"全部"|L}</option>
                <option value="1">{"测试"|L}</option>
                <option value="0">{"商用"|L}</option>
            </select>
        </div>
        <div class="line none">
            <label>{"订购产品"|L}：</label>
            <select name="u_product_id" class="autofix" action="?m=product&a=option" >
                <option value="">{"全部"|L}</option>
            </select>
        </div>
        <div class="line">
            <div class="line" style="float:left;width: 50px;"><label class="title" style="">{"增值功能"|L}：</label></div>
            <div class="line" style="width:550px;"><div id="product_select" class="autofix  autocheck"  value="{$item.u_p_function|escape:"html"}" action="?m=product&a=ip_option&e_id={$data.e_id}"></div></div>
        </div>

        <h3 class="title">{"详细属性"|L}<a class="toggle alink" data="detailed">{"展开"|L}↓</a></h3>
        <div class="detailed none">
            <div class="line none">
                <label>{"头像"|L}：</label>
                <select name="u_pic">
                    <option value="">{"全部"|L}</option>
                    <option value="1">{"有头像"|L}</option>
                    <option value="0">{"无头像"|L}</option>
                </select>
            </div>

            <div class="line none">
                <label>{"性别"|L}：</label>
                <select name="u_sex">
                    <option value="">{"全部"|L}</option>
                    <option value="M">{"男"|L}</option>
                    <option value="F">{"女"|L}</option>
                </select>
            </div>

            <div class="line">
                <label>{"手机号"|L}：</label>
                <input autocomplete="off"  class="autosend" name="u_mobile_phone" type="text" />
            </div>
            <div class="line none">
                <label>UDID：</label>
                <input autocomplete="off"  class="autosend" name="u_udid" type="text" />
            </div>
            <div class="line">
                <label>IMSI：</label>
                <input autocomplete="off"  class="autosend" name="u_imsi" type="text" />
            </div>
            <div class="line">
                <label>IMEI：</label>
                <input autocomplete="off"  class="autosend" name="u_imei" type="text" />
            </div>
            <div class="line">
                <label>ICCID：</label>
                <input autocomplete="off"  class="autosend" name="u_iccid" type="text" />
            </div>
            <div class="line">
                <label>MAC：</label>
                <input autocomplete="off"  class="autosend" name="u_mac" type="text" />
            </div>
            <div class="line">
                <label>{"终端类型"|L}：</label>
                <input autocomplete="off"  class="autosend" name="u_terminal_type" type="text" />
            </div>
        </div>
        <div class="buttons right">
            <a form="form" class="button waitsubmit"><i class="icon-search"></i>{"查询"|L}</a>
        </div>
    </form>
</div>

<div class="content"></div>
{/strip}
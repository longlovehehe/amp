<!-- 基本信息 --><div style="width:549px; float: left;"><div class="userinfo">        <h2 class="title _3_jpg" ><em class='none'>{"帐号信息"|L}</em></h2>        <div class="list" style="padding-left: 150px;">    <!--帐号状态-->    <ul class="list logininfo">               <li>{"姓名"|L}：{if $smarty.session.as_account_id eq ""}{$smarty.session.ag.ag_conname} {$smarty.session.ag.ag_username}{else}{$smarty.session.ag_as.as_lastname} {$smarty.session.ag_as.as_username}{/if}</li>                {*<li>{"登陆帐号"|L}：{$smarty.session.ag.ag_id}</li>*}                <li>{"手机"|L}：{if $smarty.session.as_account_id eq ""}{$smarty.session.ag.ag_phone}{else}{$smarty.session.ag_as.as_phone}{/if}</li>                <li>{"邮箱"|L}：{if $smarty.session.as_account_id eq ""}{$smarty.session.ag.ag_mail}{else}{$smarty.session.ag_as.as_mail}{/if}</li>                <li>{"传真"|L}：{if $smarty.session.as_account_id eq ""}{$smarty.session.ag.ag_fox}{else}{$smarty.session.ag_as.as_fox}{/if}</li>                {*<li>上次登录地址：{$smarty.session.ag.om_lastlogin_ip}</li>*}                <li>{"上次登录时间"|L}：{if $smarty.session.as_account_id eq null}{$list.ag_lastlogin_time}{else}{$smarty.session.ag_as.as_lastlogin_time}{/if}</li>    </ul>        <h2 style="border-bottom: 1px solid #A93A3A"></h2></div></div><br/><br/><div class="anbbs">        <h2>{"代理商信息"|L}</h2></div>    <div class="form mrbt20">        <div class="block ">            <label class="title">{"代理商编号"|L}：</label>            <span class='ellipsis2 ctips'>{$list.ag_number}</span>        </div>        <div class="block ">            <label class="title">{"代理商名称"|L}：</label>            <span title='{$list.ag_name}' class='ellipsis2 ctips' >{$list.ag_name|mbsubstr:20}</span>        </div>        <div class="block ">            <label class="title">{"地址"|L}：</label>            <span title='{$list.ag_addr}' class='ellipsis2 ctips' >{$list.ag_addr|mbsubstr:20}</span>        </div>        <div class="block ">            <label class="title">{"联系人姓名"|L}：</label>            <span title='{$list.ag_conname} {$list.ag_username}' class='ellipsis2 ctips' >{$list.ag_conname} {$list.ag_username}</span>        </div>        <div class="block ">            <label class="title">{"区域"|L}：</label>            <span>{$area_str}</span>        </div>        <div class="block none">            <label class="title">{"状态"|L}：</label>            <span title="{"不启用"|L}|{"启用"|L}|{"处理中"|L}|{"发布失败，启用时不能迁移MDS,只有具有录制功能才能迁移VCR。处于处理中时无法编辑企业。当前状态{$data.e_status}"|L}">{$data.e_status|modifierStatus}</span>        </div>        <div class="block {if $list.ag_level eq 1}none{/if}">            <label class="title">{"下级代理"|L}：</label>            <span>{$ag_list_num}</span>        </div>        <div class="block ">            <label class="title">{"企业数"|L}：</label>            <span>{$ep_num}</span>        </div>        <div class="block none">            <label class="title">{"流量卡"|L}：</label>            <span>{$gprnum}</span>        </div>        <div class="block ">            <label class="title">{"手机用户数"|L}：</label>            <span>{$phone} | {$list.ag_phone_num}</span>        </div>        <div class="block ">            <label class="title">{"调度台用户数"|L}：</label>            <span>{$dispatch} | {$list.ag_dispatch_num}</span>        </div>        <div class="block ">            <label class="title">{"GVS用户数"|L}：</label>            <span>{$gvs} | {$list.ag_gvs_num}</span>        </div>          <div class="{if $smarty.session.ag.ag_level+1 eq 2}none{/if}">         <div class="block ">            <label class="title">{"基本功能价格"|L}：</label>            <span  >{$pri_info.basic_price_amp}</span>        </div>         <div class="block ">            <label class="title">{"Console用户价格"|L}：</label>            <span  >{$pri_info.console_price_amp}</span>        </div>          </div>    </div></div><!---右侧联系公司----><div class="none"  style="float:right;width:200px;border-left: 1px solid #ccc;height:660px;">    <div class="" style="padding:10px">        <h1 class="">{"尊敬的用户"|L}：</h1>        <br/>        <h3>{"您在使用过程中有不明白的地方，请联系客服"|L}：</h3>        <br/>            <div>            <div style="float: left;width:60px;">{"电话"|L}：</div>            <div style="word-wrap:break-word;padding-left: 80px;">                <h3>400-800-8888</h3>                <h3>010-61234567</h3>            </div>            </div>        <br/>                <br/>        <div>            <div style="float: left;width:60px;">{"邮箱"|L}：</div>            <div style="word-wrap:break-word;padding-left: 80px;">support@zed-3.com.cn</div>        </div>        <br/>        <h3></h3>    </div></div>
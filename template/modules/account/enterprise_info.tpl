<div class="toptoolbar ">
{*    <a class="export button">{"导出"|L}</a>*}
    <a href="?m=account&a=index&ep_id={$info.er_ag_path|mb_substr:18:12}&start={$smarty.request.start}&type=amp" class="button orange">{"返回"|L}</a>
</div>
<h2 class="title"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{$info.er_name}-{"详情"|L}</h2>
<form class="data">
    <input type="hidden" name="start" value="{$smarty.request.start}">
    <input type="hidden" name="er_id" value="{$smarty.request.er_id}">
    <input type="hidden" name="ep_id" value="{$smarty.request.ep_id}">
    <input type="hidden" name="type" value="amp">
    <div class="form mrbt20">
        <div class="block ">
            <label class="title">{"帐户号码"|L}：</label>
            <span title='{$info.er_create_name|mbsubstr:6:""}{$info.er_id}' style='max-width: 350px;height: 20px;'>{if $info.er_create_name eq 0}000000{else}{$info.er_create_name|mbsubstr:6:""}{/if}{$info.er_id}</span>
        </div>
        <div class="block ">
            <label class="title">{"企业名称"|L}：</label>
            <span style='max-width: 350px;height: 20px;'>{$info.er_name}</span>
        </div>
        <div class="block ">
            <label class="title">{"计费日期"|L}：</label>
            <span>{$start_date}~{$max_date}</span>
        </div>
        <div class="block ">
            <label class="title">{"企业地址"|L}：</label>
            <span title='{$info.e_addr}'  style='max-width: 350px;height: 20px;'>{$info.er_addr|mbsubstr:20}</span>
        </div>
        <div class="block ">
            <label class="title">{"开户日期"|L}：</label>
            <span title=''  style='max-width: 350px;height: 20px;'>{$info.er_create_time}</span>
        </div>
    
    {*<div class="block ">
        <label class="title">{"产品功能费"|L}：</label>
        <span>{"合计"|L}</span>
    </div>*}
    <div class="block ">
        <label class="title" style="font-size: 18px;font-weight: bold;">{"费用详情"|L}：</label>
        <br/>
        <div class="form mrbt20 toolbar">
            <div class="block">
                <label style="padding: 0px 200px 0px 0px;">{"收费项目"|L}:</label>
            </div>
            <div class="block">
                <label style="padding: 0px 200px 0px 0px;">{"基础功能费"|L}：{$price.basic_price_amp.price}</label>
                <label>{"Console功能费"|L}：{$price.console_price_amp.price}</label>
            </div>
     
        </div>
        <div class="cost_bg">
            {foreach name=list key=key item=item from=$info_price}
                <div class="autofg"><label>{$smarty.foreach.list.iteration}.</label>{$item.name|L}：{$item.price}</div>
            {/foreach}
        </div>
    </div>
        <div style="border: 1px solid #CCCCCC;">
    <table class="base full">
        <tr class='head' >
            {*<th width="25px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>*}
            <th width="30px">{"编号"|L}</th>
            <th width="60px">{"用户名称"|L}</th>
            <th width="70px">{"用户ID"|L}</th>
            <th width="80px">{"用户类型"|L}</th>
            <th width="90px">{"增值功能"|L}</th>
            <th width="150px">{"计费日期"|L}</th>
            <th width="70px">{"计费比例"|L}</th>
            <th width="65px">{"金额"|L}</th>
        </tr>
    </table>
         <div style=" height:400px; overflow-y: scroll;">
        <table class="base full">
        {foreach name=list key=key item=item from=$list}
                {if $smarty.foreach.list.iteration eq $item.count}
                <tr>
                  {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                  <td width="30px">{$smarty.foreach.list.iteration}</td>
                  <td width="55px">{$item.ur_name|mbsubstr:6:"..."}</td>
                  <td width="70px">{$item.ur_number}</td>
                  <td width="80px">{"{$item.ur_sub_type}"|L}</td>
                  <td width="90px">{$item.ur_p_function}</td>
                  <td width="150px">{$start_date}~{$max_date}</td>
                  <td width="65px">{$item.ur_charg_ratio_amp*100|default:'0'}%</td>
                  <td width="50px">{($item.ur_sum_money_amp+$item.ur_money_amp)|string_format:"%.2f"}</td>
              </tr>
              <tr style="border-bottom: 1px solid #ccc;border-top: 1px solid #ccc;background: #7e7e7e;">
                  <td width="30px"></td>
                  <td width="55px"></td>
                  <td width="70px"></td>
                  <td width="80px"></td>
                  <td width="90px"></td>
                  <td width="150px"></td>
                  <td width="60px"></td>
                  <td width="50px"></td>
              </tr>
              {else}
              <tr>
                  {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                  <td width="30px">{$smarty.foreach.list.iteration}</td>
                  <td width="55px">{$item.ur_name|mbsubstr:6:"..."}</td>
                  <td width="70px">{$item.ur_number}</td>
                  <td width="80px">{"{$item.ur_sub_type}"|L}</td>
                  <td width="90px">{$item.ur_p_function}</td>
                  <td width="150px">{$start_date}~{$max_date}</td>
                  <td width="60px">{$item.ur_charg_ratio_amp*100|default:'0'}%</td>
                  <td width="50px">{($item.ur_sum_money_amp+$item.ur_money_amp)|string_format:"%.2f"}</td>
              </tr>
              {/if}
        {/foreach}

    </table>
         </div>
        <div style="padding: 10px 0px;">
                <div style="float: left;padding-right: 250px;height: 24px;text-align: center;">{"注:Phone类型的用户的费用包含基础功能费+增值功能费"|L}</div>
                <div style="font-size: 24px;font-weight: bold;">Total:<span>{$total_price|string_format:"%.2f"}</span></div>
                <div style="clear: both;"></div>
        </div>
    </div>
    <div  style=" padding-top: 10px;float: right;font-size: 16px;font-weight: bold;">
        <label>{"企业用户数"|L}：</label>
        <span>{$smarty.foreach.list.iteration}</span>
    </div>
</form>
    <!--输出台-->
<iframe id="ifr" name="hidden_frame"></iframe>

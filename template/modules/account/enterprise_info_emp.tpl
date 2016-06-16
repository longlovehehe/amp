<div class="toptoolbar ">
    <a class="export button">{"导出"|L}</a>
    <a href="?m=account&a=index&start={$smarty.request.start}" class="button orange">{"返回"|L}</a>
</div>
<h2 class="title"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{$info.er_name}-{"详情"|L}</h2>
<form class="data">
    <input type="hidden" name="start" value="{$smarty.request.start}">
    <input type="hidden" name="er_id" value="{$smarty.request.er_id}">
    <input type="hidden" name="ep_id" value="{$smarty.request.ep_id}">
    <input type="hidden" name="type" value="emp">
    <div class="form mrbt20 toolbar" id="hide">
    <div class="block ">
        <label class="title">{"账单日期"|L}：</label>
        <span>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</span>
    </div>
    <div class="block ">
        <label class="title">{"帐户号码"|L}：</label>
        <span title='{$info.er_create_name|mbsubstr:6:""}{$info.er_id}' style='max-width: 350px;height: 20px;'>{if $info.er_create_name eq 0}000000{else}{$info.er_create_name|mbsubstr:6:""}{/if}{$info.er_id}</span>
    </div>
    <div class="block ">
        <label class="title">{"账单号码"|L}：</label>
        <span style='max-width: 350px;height: 20px;'>{$info.pre_count_number}</span>
    </div>
    <div class="block ">
        <label class="title">{"客户名称"|L}：</label>
        <span style='max-width: 350px;height: 20px;'>{$info.er_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"计费日期"|L}：</label>
        <span style='max-width: 350px;height: 20px;'>{$start_date}~{$max_date}</span>
    </div>

    <div class="block ">
        <label class="title">{"企业地址"|L}：</label>
        <span title='{$info.er_addr}'  style='max-width: 350px;height: 20px;'>{$info.er_addr|mbsubstr:20}</span>
    </div>
   <div class="block ">
        <label class="title">{"开户日期"|L}：</label>
        <span title="">{$info.er_create_time}</span>
    </div>
    <div class="line ">
        <label class="title">{"开户银行"|L}：</label>
        <input type="text" maxlength="64" autocomplete="off"  name="open_bank" class="autosend" style="width:220px;height: 24px;border-bottom: 5px solid #cccc;"/>
    </div>
    <div class="line">
        <label class="title">{"开户行账号"|L}：</label>
         <input type="text" maxlength="64" autocomplete="off"  onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onblur="if(!this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?|\.\d*?)?$/))this.value=this.o_value;else{
            if(this.value.match(/^\.\d+$/))this.value=0+this.value;if(this.value.match(/^\.$/))this.value=0;this.o_value=this.value}" name="open_bank_account" class="autosend" style="width:220px;height: 24px;border-bottom: 5px solid #cccc;"/>
    </div>
   
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
                <label style="padding: 0px 200px 0px 0px;">{"基础功能费"|L}：{$price.basic_price.price}</label>
                <label>{"Console功能费"|L}：{$price.console_price.price}</label>
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
            <th width="5%"><div>{"编号"|L}</div></th>
            <th width="11%">{"用户名称"|L}</th>
            <th width="13%">{"用户ID"|L}</th>
            <th width="12%">{"用户类型"|L}</th>
            <th width="15%">{"增值功能"|L}</th>
            <th width="24%">{"计费日期"|L}</th>
            <th width="10%">{"计费比例"|L}</th>
            <th width="10%">{"金额"|L}</th>
        </tr>
    </table>
         <div style=" height:400px; overflow-y: scroll;">
        <table class="base full">
        {foreach name=list key=key item=item from=$list}
             {if $smarty.foreach.list.iteration eq $item.count}
                <tr>
                  {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                  <td width="4%">{$smarty.foreach.list.iteration}</td>
                  <td width="11%">{$item.ur_name|mbsubstr:6:"..."}</td>
                  <td width="14%">{$item.ur_number}</td>
                  <td width="12%">{"{$item.ur_sub_type}"|L}</td>
                  <td width="16%">{$item.ur_p_function}</td>
                  <td width="24%">{$item.start_date}~{$item.max_date}</td>
                  <td width="10%">{$item.ur_charg_ratio_amp*100|default:'0'}%</td>
                  <td width="8%">{($item.ur_sum_money_amp+$item.ur_money_amp)|string_format:"%.2f"}</td>
              </tr>
              <tr>
                  {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                  <td width="4%"></td>
                  <td width="11%"></td>
                  <td width="14%"></td>
                  <td width="12%"></td>
                  <td width="16%"></td>
                  <td width="24%"></td>
                  <td width="10%"></td>
                  <td width="8%"></td>
              </tr>
              {else}
              <tr>
                  {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                  <td width="5%">{$smarty.foreach.list.iteration}</td>
                  <td width="11%">{$item.ur_name|mbsubstr:6:"..."}</td>
                  <td width="14%">{$item.ur_number}</td>
                  <td width="12%">{"{$item.ur_sub_type}"|L}</td>
                  <td width="16%">{$item.ur_p_function}</td>
                  <td width="24%">{$item.start_date}~{$item.max_date}</td>
                  <td width="10%">{$item.ur_charg_ratio_amp*100|default:'0'}%</td>
                  <td width="8%">{($item.ur_sum_money_amp+$item.ur_money_amp)|string_format:"%.2f"}</td>
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
    <br />
    <br />
        <div class="form mrbt20 toolbar" id="hide">
    <div class="line ">
        <label class="title">{"其他费用"|L}：</label>
        <span><input type="number" oninput="get_price();" oncopy="return false;" onpaste="return false;" oncut="return false;" maxlength="64" autocomplete="off"  name="other_price" class="autosend calculation" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"备注"|L}：</label>
        <span><input type="text" maxlength="128" autocomplete="off" name="remarks" class="autosend" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"税前费用合计"|L}：</label>
        <span><input type="text" oncopy="return false;" onpaste="return false;" oncut="return false;" maxlength="64" autocomplete="off" name="pre_total" class="autosend calculation" disabled="true" value="{$total_price|string_format:"%.2f"}" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"税率"|L}：</label>
        <span><input type="number" min="0" oninput="get_pte();" oncopy="return false;" onpaste="return false;" oncut="return false;" maxlength="8" autocomplete="off"  name="pte" class="autosend calculation" value="" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/>%</span>
    </div>
        <br />
        <br />
    <div class="line" style="float: right;font-size: 20px;font-weight: bold;">
        <label>{"税后合计"|L}：</label>
        <span><input type="text" oncopy="return false;" onpaste="return false;" oncut="return false;" name="total" class="autosend" value="{$total_price|string_format:"%.2f"}" style="width:120px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    </div>
</form>
    <!--输出台-->
<iframe id="ifr" name="hidden_frame"></iframe>
 <script>
         if($("input[name=ep_id]").val()=="0"){
            $("#hide").addClass("none");
        }else{
            $("#hide").removeClass("none");
        }
        
        $(".calculation").bind("change",function(){
             var pre_total={$total_price};
            var total=0;
            var other_price=$("input[name=other_price]").val()==""?0:$("input[name=other_price]").val();
            var pte=$("input[name=pte]").val()==""?0:$("input[name=pte]").val();
            //var pre_total=$("input[name=pre_total]").val()==""?0:$("input[name=pre_total]").val();
            $("input[name=pre_total]").val()==""?0:$("input[name=pre_total]").val((parseFloat(other_price)+parseFloat(pre_total)).toFixed(2));
            total=(parseFloat(other_price)+parseFloat(pre_total))*(1+parseFloat(pte)/100);
            
            $("input[name=total]").val(total.toFixed(2));
        });
    </script>
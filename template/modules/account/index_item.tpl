<form class="data">
    <div class="form mrbt20 toolbar" id="hide">
    <div class="block ">
        <label class="title">{"账单日期"|L}：</label>
        <span style='max-width: 350px;height: 20px;'>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</span>
    </div>
    <div class="block ">
        <label class="title">{"帐户号码"|L}：</label>
        <span title='{$data.ar_number}' style='max-width: 350px;height: 20px;'>{$data.ar_number|mbsubstr:20}</span>
    </div>
    <div class="block ">
        <label class="title">{"账单号码"|L}：</label>
        <span>{$data.pre_count_number}</span>
    </div>
    <div class="block ">
        <label class="title">{"客户名称"|L}：</label>
        <span title='{$data.ar_name}'  style='max-width: 350px;height: 20px;'>{$data.ar_name|mbsubstr:20}</span>
    </div>
    <div class="block ">
        <label class="title">{"计费日期"|L}：</label>
        <span title="">{$start_date}~{$max_date}</span>
    </div>
    <div class="block ">
        <label class="title">{"客户地址"|L}：</label>
        <span title='{$data.ar_addr}'  style='max-width: 350px;height: 20px;'>{$data.ar_addr|mbsubstr:20}</span>
    </div>
    
    
    <div class="block ">
        <label class="title">{"开户日期"|L}：</label>
        <span title="">{$data.ar_create_time}</span>
    </div>
    <div class="line ">
        <label class="title">{"开户银行"|L}：</label>
        <input type="text" maxlength="64" name="open_bank" class="autosend" style="width:220px;height: 24px;border-bottom: 5px solid #cccc;"/>
    </div>
    <div class="line">
        <label class="title">{"开户行账号"|L}：</label>
         <input type="text" maxlength="64" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onblur="if(!this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?|\.\d*?)?$/))this.value=this.o_value;else{
            if(this.value.match(/^\.\d+$/))this.value=0+this.value;if(this.value.match(/^\.$/))this.value=0;this.o_value=this.value}" name="open_bank_account" class="autosend" style="width:220px;height: 24px;border-bottom: 5px solid #cccc;"/>
    </div>
       
    </div>
    <div class="block ">
        <label class="title" style="font-size: 18px;font-weight: bold;">{"费用详情"|L}：</label>
        <br/>
        <div class="form mrbt20 toolbar">
            <div class="block">
                <label style="padding: 0px 200px 0px 0px;">{"收费项目"|L}:</label>
            </div>
            <div class="block">
                <label style="padding: 0px 200px 0px 0px;">{"基础功能费"|L}：{if $res.ep_id neq 0}{$price.basic_price_amp.price}{else}{$price.basic_price.price}{/if}</label>
                <label>{"Console功能费"|L}：{if $res.ep_id neq 0}{$price.console_price_amp.price}{else}{$price.console_price.price}{/if}</label>
            </div>
        </div>
        <div class="cost_bg">
            {foreach name=list key=key item=item from=$info}
                <div class="autofg"><label>{$smarty.foreach.list.iteration}.</label>{$item.name|L}：{$item.price}</div>
            {/foreach}
        </div>
    </div>
    <div style="border: 1px solid #CCCCCC;"> 
        <table class="base full">
            <tr class='head'>
                {*<th width="25px"><input autocomplete="off"  type="checkbox" id="checkall" /></th>*}
                <th width="50px">{"编号"|L}</th>
                <th width="80px">{"企业名称"|L}</th>
                <th width="100px">{"帐户号码"|L}</th>
    {*            <th width="80px">{"产品功能费"|L}</th>*}
                <th width="100px">{"企业用户数"|L}</th>
                <th width="80px">{"合计"|L}</th>
                <th width="95px">{"查看"|L}</th>
            </tr>
        </table>
        <div style="height:300px; overflow-y:scroll;">
            <table class="base full">
                {foreach name=list item=item from=$list}
                <tr>
                    {*<td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>*}
                    <td width="50px">{$smarty.foreach.list.iteration}</td>
                    <td width="80px">{$item.er_name|default:'暂无记录'}</td>
                    <td width="100px">{*{$item.e_create_name|mbsubstr:6:""}*}{$item.er_id}</td>
        {*            <td title="产品功能费:{$item.sum}<br />基础功能费:{$item.basic_price}+{$item.console_price}">{$item.sum+$item.basic_price+$item.console_price|default:'暂无记录'}</td>*}
                    <td width="100px">{$item.preson_total}</td>
                    <td width="80px">{($item.er_sum_money_amp+$item.er_sum_money_p_function_amp)|string_format:"%.2f"}</td>
                    {if $smarty.request.type eq emp}
                        <td width="80px"><a href="?m=account&a=show_ep_info_emp&er_id={$item.er_id}&ep_id={$item.er_create_name}&start={$res.start}" class="link edit">{"详情"|L}</a></td>
                    {else}
                        <td width="80px"><a href="?m=account&a=show_ep_info_amp&er_id={$item.er_id}&ep_id={$item.er_create_name}&start={$res.start}" class="link edit">{"详情"|L}</a></td>
                    {/if}
                </tr>
                {/foreach}
            </table>
        </div>
            <div style="padding: 10px 0px;">
                <div style="float: left;padding-right: 220px;height: 24px;text-align: center;">{"注:Phone类型的用户的费用包含基础功能费+增值功能费"|L}</div>
                <div style="font-size: 24px;font-weight: bold;">Total:<span>{$total|string_format:"%.2f"}</span></div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div  style=" padding-top: 10px;float: right;font-size: 16px;font-weight: bold;">
        <label>{"企业数"|L}：</label>
        <span>{$smarty.foreach.list.iteration}</span>
    </div>
    <br>
    <br>
    <div class="form mrbt20 toolbar" id='hide1'>
    <div class="line ">
        <label class="title">{"其他费用"|L}：</label>
        <span><input type="number" min="0" oninput='get_price();' oncopy="return false;" onpaste="return false;" oncut="return false;" autocomplete="off"  name="other_price" class="autosend calculation" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"备注"|L}：</label>
        <span><input type="text" autocomplete="off" name="remarks" class="autosend" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"税前费用合计"|L}：</label>
        <span><input type="text" autocomplete="off" name="pre_total" class="autosend calculation" disabled="true" value="{$total|string_format:"%.2f"}" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    <div class="line ">
        <label class="title">{"税率"|L}：</label>
        <span><input type="number" oninput="get_pte();" min="0" oncopy="return false;" onpaste="return false;" oncut="return false;" autocomplete="off"  name="pte" class="autosend calculation" value="" style="width:180px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/>%</span>
    </div>
        <br />
        <br />
    <div class="line" style="float: right;font-size: 20px;font-weight: bold;">
        <label>{"税后合计"|L}：</label>
        <span><input type="text" disabled="true" name="total"  class="autosend" value="{$total|string_format:"%.2f"}" style="width:120px;height: 24px;border-bottom: 5px solid #cccc;text-align: center;"/></span>
    </div>
    </div>
</form>
    <script>
        if($("input[name=ep_id]").val()=="0"){
            $("#hide").addClass("none");
            $("#hide1").addClass("none");
        }else{
            $("#hide").removeClass("none");
            $("#hide1").removeClass("none");
        }
        
          $(".calculation").bind("change",function(){
            var pre_total={$total};
            var total=0;
            var other_price=$("input[name=other_price]").val()==""?0:$("input[name=other_price]").val();
            var pte=$("input[name=pte]").val()==""?0:$("input[name=pte]").val();
            //var pre_total=$("input[name=pre_total]").val()==""?0:$("input[name=pre_total]").val();
             $("input[name=pre_total]").val()==""?0:$("input[name=pre_total]").val((parseFloat(other_price)+parseFloat(pre_total)).toFixed(2));
            total=(parseFloat(other_price)+parseFloat(pre_total))*(1+parseFloat(pte)/100);
            
            $("input[name=total]").val(total.toFixed(2));
        });
    </script>
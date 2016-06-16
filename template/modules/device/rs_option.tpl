{if $smarty.request.d_deployment_id == ''}

{else}
<option onclick="cancel()" {if !$e_vcr_id} selected="selected" {/if}  value="" d_deployment_id="0"  style="color: #000" d_recnum="0" diff_rec="0" d_have="0">{"不选择任何设备"|L}</option>
{foreach name=list item=item from=$list}
<option {if $e_vcr_id == $item.d_id} selected="selected" {/if} text ="pxx" value="{$item.d_id}" d_deployment_id="{$item.d_deployment_id}" style="font-size:16px" d_recnum="{$item.d_recnum}" diff_rec="{$item.d_recnum-$item.sum_rec}" d_have = "{$item.d_recnum-$item.sum_rec}">{"设备名称"|L}：{$item.d_name} {"外网IP"|L}：【{$item.d_ip2}】{"内网IP"|L}：【{$item.d_ip1}】</option>
<option value="" disabled="disabled" >【{"可用/总并发数"|L}：{$item.d_recnum-$item.sum_rec} / {$item.d_recnum}】</option>
{foreachelse}
<option value="" disabled="disabled" style="color: #000" d_recnum="0" diff_rec="0">{"该区域下没有可使用设备"|L}</option>
{/foreach}
{/if}


<script type="text/javascript">
	function cancel()
	{
		// $("#e_vcr_id").find("option[text='px']").attr("selected",false);
		// $("#e_vcr_id option[text='px']").attr("selected", false); 
		$("#e_vcr_id").find("option[text='pxx']").attr("selected",false);
		$("#e_has_vcr").val('0');
		$("#e_rs_rec").val('0');
		$(".cur_e_rs_rec").html('0');
		$('#e_rs_rec-error').remove();
		// $("#e_vcr_id").find("option[text='px']").attr("selected",true);
		// $("#e_vcr_id option[text='px']").attr("selected", true); 
	}
</script>
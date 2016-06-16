{strip}
        {foreach name=getAnList item=item from=$getAnList}
                <tr>
                        <td>{$item.an_time|date_format:"%Y-%m-%d"}</td>
                        <td>
                                <span class="ellipsis" style="width: 450px">
                                        <a href="?m=system&a=pro_details&an_id={$item.an_id}" class="alink">{$item.an_title}</a>
                                </span> 
                        </td>

                        <td><span class="ellipsis" style="width: 80px">{$item.an_area|mod_area_name} </span> </td>
                </tr>
        {/foreach}
{/strip}
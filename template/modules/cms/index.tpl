{strip}
<h2 class="title">{"{$title}"|L}</h2>

<div class="block">
    <label class="title" style="fimlay-font:微软雅黑;
           color:#535353; font-size:13px;font-weight:bold;" >{"Android软件包"|L} </label>
    <div class="t-android">
        <div class="nav_head">
            <table >
                <tr>
                    <th width="45%">{"目录"|L}</th>

                    <th width="11%">{"版本号"|L}</th>

                    <th width="45%">{"文件名"|L}</th>
                </tr>
            </table>
        </div>
        <div class="control">
            <table id="android-table" >
                {foreach name=list item=infoa from=$android_info}
                <input type="hidden" name="p_id" value="{$infoa.p_id}">
                <tr title="{'目录'|L}: 【{$infoa.p_dir}】&#10;{'版本号'|L}: 【{$infoa.p_version}】&#10;{'文件名'|L}: 【{$infoa.p_file}】" onClick="do_select(this, 1);" id="get_pid" p_id="{$infoa.p_id}" p_type="{$infoa.p_type}">
                    <td style="overflow:hidden; width:262px;">{$infoa.p_dir}</td>

                    <td style="overflow:hidden; width:64px; ">{$infoa.p_version}</td>

                    <td style="overflow:hidden; width:250px;" >{$infoa.p_file}</td>
                </tr>
                {/foreach}
            </table>
            <br />
        </div>
        {*<div class="page none_select">
            <div class="num">{$numinfoa}</div>
            <div class="turn">
                <a page="{$preva}" class="prev">{"上一页"|L}</a>
                <a page="{$nexta}" class="next">{"下一页"|L}</a>
            </div>
        </div>*}

        <div class="fk">

            <a class="button"  href = "javascript:void(0)" onclick = "new_creat();">{"新建"|L}</a>
            <a class="button"  href = "javascript:void(0)" onclick = "edit_android_dir();">{"修改"|L}</a>
            <a class="button"  href = "javascript:void(0)" onclick = "del_android_dir();">{"删除"|L}</a>
            <a class="button"  href = "javascript:void(0)" onclick = "empty_android_dir();">{"清空"|L}</a>
        </div>
    </div>
</div>
<br />

<div class="block none"><!--  -->
    <label class="title" style="fimlay-font:微软雅黑;
           color:#535353; font-size:13px;font-weight:bold;" >{"IOS软件包"|L}</label>
    <div class="t-android">
        <div class="nav_head">
            <table >
                <tr>
                    <th width="45%">{"目录"|L}</th>

                    <th width="11%">{"版本号"|L}</th>

                    <th width="45%">{"文件名"|L}</th>
                </tr>
            </table>
            </table>
        </div>
        <div class="control">
            <table id="ios-table">
                {foreach name=list item=infoi from=$ios_info}
                <tr title="目录: {$infoi.p_dir}, 版本号: {$infoi.p_version}, 文件名: {$infoi.p_file}" onClick="do_select(this, 2);"  p_id="{$infoi.p_id}" p_type="{$infoi.p_type}">
                    <td  style="overflow:hidden; width:262px;" >{$infoi.p_dir}</td>
                    <td style="overflow:hidden; width:64px;">{$infoi.p_version}</td>
                    <td style="overflow:hidden;width:250px; ">{$infoi.p_file}</td>
                </tr>
                {/foreach}
            </table>
            <br />
        </div>
        {*<div class="page none_select">
            <div class="num">{$numinfoa}</div>
            <div class="turn">
                <a page="{$preva}" class="prev">{"上一页"|L}</a>
                <a page="{$nexta}" class="next">{"下一页"|L}</a>
            </div>
        </div>*}

        <div class="fk">

            <a class="button"  href = "javascript:void(0)" onclick = "new_creat1();">{"新建"|L}</a>
            <a class="button"  href = "javascript:void(0)" onclick = "edit_ios_dir();">{"修改"|L}</a>
            <a class="button" id="del" class="mrlf5 link" href = "javascript:void(0)" onclick = "del_ios_dir();">{"删除"|L}</a>
            <a class="button"  href = "javascript:void(0)" onclick = "empty_ios_dir();">{"清空"|L}</a>
        </div>
    </div>
</div>
<br />

<div style="clear: both;"></div>
<br />
<br />
<br />

<form id="form" action="?m=cms&a=upload_soft" method="post" name="work_form" enctype="multipart/form-data">
    <div  id="light" class="white_content">
        <input type="hidden" name="an_or_ios" value="">
        <input type="hidden" name="flag" value="save">
        <input type="hidden" id="" name="pid" value="">
        <input type="hidden" id="ptype" name="ptype" value="">
        <input type="hidden" name="browsversion" value="">

        <div style="background-color:#DCE0E1;"><div style="float:left;width: 20px;">&nbsp;</div><div class="c_dir">{"新建目录"|L}</div></div>
        <div class="conhei"></div>
        <div class="block"  style="height:35px;">
            <label class="title">{"目录名称"|L}: </label>
            <input type="text" id="ptt_soft" name="dir_name" value="" dir_name="true" autocomplete="off" required="true" >
        </div>
        <div class="block" style="height:35px;">
            <label style="float: left;" class="title" >{"软件包"|L}: </label>
            {*<input id="fileSelector" type="file" name="soft_name" style="width:300px;" soft_name="true" onchange="getFiles(this);" value="">*}
            <div >
                &nbsp;&nbsp;&nbsp;<input type="text" name="path" readonly style="width: 130px;">
                <a id="zdll" href="javascript:void(0);" >{"浏览"|L}
                    <input type="file" soft_name="true" name="soft_name" id="fileSelector"  onchange="getFiles(this);">
                </a>
            </div>
        </div>
        <div class="block" style="height:35px;">
            <label class="title">{"软件版本"|L}: </label>
            <input type="text" name="ptt_version" ptt_version="true" autocomplete="off" required="true" >
        </div>

        <div class="conhei"></div>
        <div class="block" style="float:right">
            {*                            <a class=" button" onclick="up_soft();" >{"保存"|L}</a>*}
            <input type="button" value="{'保存'|L}" name="button" class="button" onclick="do_set();"/>&nbsp;&nbsp;&nbsp;
            <a class="button" href = "javascript:void(0)" onclick = "closed();">{"关闭"|L}</a>
        </div>
    </div>
</form>
<div class="content"></div>
<div id="dialog-confirm" class="hide" title="删除选中项？">
    <p>{"确定要删除该产品吗"|L}？</p>
</div>
{/strip}
<script  {"type='ready'"}>
    $(document).ready(function () {

    })
</script>
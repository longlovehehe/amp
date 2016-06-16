
<!-- 基本信息 -->
<div class="userinfo">
        <h2 class="title _3_jpg" ><em class='none'>帐号信息</em></h2>
        <div class="uname">姓名：{$smarty.session.own.om_id}</div>
        <ul class="list admintype">
                <li><label class='title'>管理员级别：</label>{$smarty.session.own.om_id|level}</li>
                <li title='{$smarty.session.own.om_area|mod_area_name}'><label class='title'>管辖区域：</label><span class='ellipsis2' style='width: 240px;'>{$smarty.session.own.om_area|mod_area_name}</span></li>
                <li><label class='title'>管辖企业：</label>{$en}</li>
                <li><label class='title'>管辖设备：</label>{$device}</li>
        </ul>
        <!--帐号状态-->
        <ul class="list logininfo">
                <li>上次登录地址：{$smarty.session.own.om_lastlogin_ip}</li>
                <li>上次登录时间：{$smarty.session.own.om_lastlogin_time}</li>
        </ul>
</div>
<div class="anbbs">
        <h2 class="title" >系统公告</h2>

        <p class='info'>提示：公告往下滚动，查看更多</p>
        <form class="none" id="form" action="?m=system&a=index_item" method="post" data='{literal}{"type":"append"}{/literal}'>
                <div  class="toolbar ">
                        <input autocomplete="off"  name="page" value="-1" type="hidden" />
                        <input autocomplete="off"  type="hidden" value="10" name="num" />
                        <a form="form" class="submit none"></a>
                </div>
        </form>
        <div class="content newtable">
                <table class="base full content">
                        <tr>
                                <th width="100px">发布日期</th>
                                <th>公告内容</th>
                                <th width="100px">发布区域</th>
                        </tr>
                </table>
        </div>

        <a class="addmore none">加载更多</a>
</div>

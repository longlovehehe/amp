{strip}
<h2 class="title">{"{$title}"|L}</h2>
<form id="form" class="base mrbt10" action="?modules=agentsub&action=sub_save">
    <input autocomplete="off"  value="{$data.do}" name="do" type="hidden" />
    <div class="block">
        <label class="title">{"帐号"|L}：</label>
        {if $data.do eq "edit"}
        <input chinese="true" autocomplete="off"   maxlength="32" value="{$info.as_account_id}" name="as_account_id" type="text" required="true" readonly="true" />
        {else}
        <input chinese="true" autocomplete="off" as_account_id="true" maxlength="32" name="as_account_id" type="text" required="true" />
        {/if}
    </div>
    <div class="block">
        <label class="title">{"密码"|L}：</label>
        <input autocomplete="off"  maxlength="32" paswd="true" id="password" value="{$info.as_passwd}" name="as_passwd" type="password" required="true" />
        {if $data.do eq "edit"}
        <label class="show_passwd" style="font-size: 12px;color: #a43838;">{"查看密码"|L}</label>
        {/if}
    </div>
    <div class="block">
        <label class="title">{"名字"|L}：</label>
       <input  maxlength="32" autocomplete="off" placeholder="{'名字'|L}" maxlength="32" value="{$info.as_lastname}" chinese="true" name="as_lastname" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"姓氏"|L}：</label>
       <input  maxlength="32" autocomplete="off"  placeholder="{'姓氏'|L}" maxlength="32" value="{$info.as_username}" chinese="true" name="as_username" type="text"  required="true" />
    </div>
    <div class="block">
        <label class="title">{"手机号"|L}：</label>
        <input class="mobile-number" mobile1="true" type="text" style="height: 28px;width: 245px;border:1px solid #ccc;" name="as_phone" value="{$info.as_phone}" required="true"/>
        {*<input autocomplete="off"  maxlength="32" value="{$data.em_phone}" name="em_phone" type="text" required="true" mobile="true" />*}
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <input autocomplete="off"  maxlength="32" value="{$info.as_mail}" name="as_mail" type="text" required="true"  email="true"  />
    </div>
    <div class="block none">
        <label class="title">{"安全登录"|L}：</label>
        <div class="checkbox inline" value="{$info.em_safe_login}">
            <input autocomplete="off"  name="em_safe_login" type="checkbox" />
        </div>
    </div>
    <div class="block">
        <label class="title">{"描述"|L}：</label>
        <input chinese="true" maxlength="1024" autocomplete="off" value="{$info.as_desc}" name="as_desc" type="text" />
    </div>
    <div class="buttons mrtop40">
        <a goto="?m=agentsub&a=index" form="form" class="ajaxpost_u button normal">{"保存"|L}</a>
        <a class="goback button">{"取消"|L}</a>
    </div>
</form>
{/strip}

<script>

    $(document).ready(function () {

        $("a.ajaxpost_u").click(function () {
            {if $data.do neq "edit"}
            $.ajax({
                url:"?m=agentsub&a=check_name",
                data:{
                    name:$("input[name=as_account_id]").val()
                },
                success:function(res){
                        if(res=="1"){
                             layer.closeAll('tips');
                        }else{
                            layer.tips("{"用户名已存在"|L}",$("input[name=as_account_id]"),
                            {
                                tips:[1, '#A83A3A']
                            }
                          );
                           exit();
                        }
                }
            });
            {/if}
            if ($("#form").valid()) {

                var form = $("a.ajaxpost_u").attr("form");

                var url = $("#" + form).attr("action");

                $.ajax({

                    url: url,

                    method: "POST",

                    dataType: "json",

                    data: $("#form").serialize(),

                    success: function (result) {
                        if (result.msg == "{'更改为GVS用户会丢失群组信息，是否更改'|L}？") {

                            confirm2(result.msg);

                        } else {

                            notice(result.msg, $("a.ajaxpost_u").attr("goto"));

                        }

                    }



                });

            }

        });

    });

</script>

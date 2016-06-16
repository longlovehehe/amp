/* The file is auto create */
$("input[name=as_account_id]").on("blur",function(){
    $.ajax({
        url:"?m=agentsub&a=check_name",
        data:{name:$("input[name=as_account_id]").val()},
        success:function(res){
                if(res=="1"){
                     layer.closeAll('tips');
                }else{
                    layer.tips("<%'用户名已存在'|L%>",$("input[name=as_account_id]"),
                    {
                        tips:[1, '#A83A3A']
                    }
                  );
                }
        }
        });
});
/*
$("input[name=as_account_id]").on("focus",function(){
    $("input[name=as_account_id]").on("change",function(){
        $("input[name=as_account_id]").on("blur",function(){
            jQuery.validator.addMethod("as_account_id", function (value, element) {
            var length = value.length;
            var flag = true;
            $.ajax({
                url:"?m=agentsub&a=check_name",
                data:{name:value},
                success:function(res){
                    if(res=="1"){
                        flag = true;
                        $("input[name=as_account_id]").removeAttr("as_account_id");
                    }else{
                        flag=false;
                        $("input[name=as_account_id]").attr("as_account_id","true");
                    }
                }
            });
            return flag;
            }, "<%'改用户名已存在'|L%>");
        });
    });
});
*/


//双击可编辑

   $("input[name=pi_price]").each(function(){
         $(this).dblclick(function(){
            $(this).removeAttr("readonly");
        });
        
        $(this).blur(function(){
            $(this).attr("readonly","true");
             //编辑后通过ajax保存
             var price = $(this).attr('class');
             var pi_id=$(this).attr('pi_id');
             var re= /^\d+.?\d{0,2}$/;
             if(re.test($(this).val())){
               if($(this).val()!=price){
                   $.ajax({
                            url:'?m=product&a=saveprice',
                            type:'post',
                            data:{pi_price:$(this).val(),pi_id:pi_id},
                            dataType:'json',
                            success:function(result){
                                notice(result.msg);
                             }
                     });
               }
           }else{
               notice("<%'价格格式不正确'|L%>");
           }
                      
        });
       
   });
   
$("#units_set").on('click',function(){
    $.ajax({
        url:'?m=product&a=p_units',
        //data:{units_price:$("input[name=units_price]").val(),id:$("input[name=id]").val()},
	data:{units_price:$("input[name=units_price]").val()},
        dataType:'json',
        success:function(res){
            notice(res.msg,'?m=product&a=p_basic');
        }
    });
})

$("input.currencycode").currencyCode();
//自动去除+号

var ii=$("input[name=units_price]").val();
var jj=ii.substr(0);
$("input[name=units_price]").val(jj);

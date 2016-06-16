/*
(function($){
	$.fn.citySelect=function(settings){
		if(this.length<1){return;};

		// 默认值
		settings=$.extend({
			url:"js/city.min.js",
			prov:null,
			city:null,
			dist:null,
			nodata:null,
			required:false
		},settings);
		var box_obj=this;
		var prov_obj=box_obj.find(".prov");
		var city_obj=box_obj.find(".city");
		var dist_obj=box_obj.find(".dist");
		var prov_val=settings.prov;
		var city_val=settings.city;
		var dist_val=settings.dist;
               
		var select_prehtml=(settings.required) ? "" : "<option value=''>全部</option>";
		var select_html="<option value='' disabled>全部</option>";
		var city_json;

		// 赋值一级函数
		var cityStart=function(){
			var prov_id=prov_obj.get(0).selectedIndex;
			if(!settings.required){
				prov_id--;
			};
			city_obj.empty().attr("disabled",true);
			dist_obj.empty().attr("disabled",true);
			if(prov_id<0||city_json.citylist[prov_id].list==null){

				if(settings.nodata=="none"){
					city_obj.css("display","none");
					dist_obj.css("display","none");
				}else if(settings.nodata=="hidden"){
					city_obj.css("visibility","hidden");
					dist_obj.css("visibility","hidden");
				};
				return;
			};
			
			// 遍历赋值二级下拉列表
			temp_html=select_prehtml;
			$.each(city_json.citylist[prov_id].list,function(i,city){
				temp_html+="<option value='"+city.ag_number+"'>"+city.ag_name+"</option>";
			});
			city_obj.html(temp_html).attr("disabled",false).css({"display":"","visibility":""});
			distStart();
		};

		// 赋值三级函数
		var distStart=function(){
			var prov_id=prov_obj.get(0).selectedIndex;
			var city_id=city_obj.get(0).selectedIndex;
			if(!settings.required){
				prov_id--;
				city_id--;
			};
			dist_obj.empty().attr("disabled",true);

			if(prov_id<0||city_id<0||city_json.citylist[prov_id].list[city_id].list==null){
				if(settings.nodata=="none"){
					dist_obj.css("display","none");
				}else if(settings.nodata=="hidden"){
					dist_obj.css("visibility","hidden");
				};
				return;
			};
			
			// 遍历赋值市级下拉列表
			temp_html=select_prehtml;
			$.each(city_json.citylist[prov_id].list[city_id].list,function(i,dist){
				temp_html+="<option value='"+dist.ag_number+"'>"+dist.ag_name+"</option>";
			});
			dist_obj.html(temp_html).attr("disabled",false).css({"display":"","visibility":""});
		};

		var init=function(){
			// 遍历赋值省份下拉列表
			temp_html=select_html;
			$.each(city_json.citylist,function(i,prov){
				temp_html+="<option value='"+prov.ag_number+"'>"+prov.ag_name+"</option>";
			});
			prov_obj.html(temp_html);

			// 若有传入省份与市级的值，则选中。（setTimeout为兼容IE6而设置）
			setTimeout(function(){
				if(settings.prov!=null){
					prov_obj.val(settings.prov);
					cityStart();
					setTimeout(function(){
						if(settings.city!=null){
							city_obj.val(settings.city);
							distStart();
							setTimeout(function(){
								if(settings.dist!=null){
									dist_obj.val(settings.dist);
								};
							},1);
						};
					},1);
				};
			},1);

			// 选择省份时发生事件
			prov_obj.bind("change",function(){
				cityStart();
			});

			// 选择市级时发生事件
			city_obj.bind("change",function(){
				distStart();
			});
		};

		// 设置省市json数据
		if(typeof(settings.url)=="string"){
			$.getJSON(settings.url,function(json){
				city_json=json;
				init();
			});
		}else{
			city_json=settings.url;
			init();
		};
	};
})(jQuery);
*/
/*
$(function(){	
        $.ajax({
                url:'?m=account&a=getjson',
                dataType:'json',
                async:false,
                success:function(res){
                    ep_id=res.citylist[0].ag_number;
                    ep_name=res.citylist[0].ag_name;
                    $("input[name=ep_id]").val(ep_id);
                        $("#city_5").citySelect({
                                        url:res,
                                        prov:ep_id,
                                        city:"",
                                        dist:"",
                                        nodata:"none"
                                });
                }
        });
});
*/
$("select[name=lv1]").on("change",function(){
    $("input[name=ep_id]").val($(this).children("option:selected").val());
    $("input[name=ep_name]").val($(this).children("option:selected").html());
    $("input[name=ep_id1]").val($(this).children("option:selected").val());
});
$("select[name=lv2]").on("change",function(){
    $("input[name=ep_id]").val($(this).children("option:selected").val());
    $("input[name=ep_id2]").val($(this).children("option:selected").val());
    $("input[name=ep_name]").val($(this).children("option:selected").html());
    if($(this).children("option:selected").val()==""){
        $("input[name=ep_id]").val( $("input[name=ep_id1]").val());
    }
});
$("select[name=lv3]").on("change",function(){
    $("input[name=ep_id]").val($(this).children("option:selected").val());
    $("input[name=ep_name]").val($(this).children("option:selected").html());
    if($(this).children("option:selected").val()==""){
        $("input[name=ep_id]").val( $("input[name=ep_id2]").val());
    }
    //alert($(this).children("option:selected").val());
});
$("select[name=ag_number]").trigger("change");
    $("select[name=ag_number]").on("change",function(){
        $("input[name=ep_id]").val($(this).children("option:selected").val());
        $("input[name=ep_name]").val($(this).children("option:selected").html());
        $("input[name=ep_id1]").val($(this).children("option:selected").val());
        if($("input[name=ep_id]").val()=="0"){
            $("input[name=type]").val("emp");
        }else{
            $("input[name=type]").val("amp");
        }
    send();
});

function get_price(){
    var price=$("input[name=other_price]").val();
//    if(price==""){
//        $("input[name=other_price]").val("0");
//    }
//    price=$("input[name=other_price]").val();
    var length=price.length;
    var match=/^\d+|^\d+\.\d+|^\d+\.|^\d+\.\d+$/;
    if(!match.test(price)){
        layer.tips("<%'格式不正确,请正确填写价格'|L%>",$("input[name=other_price]"), {
            tips:[1, '#A83A3A'],
            time:8000
        });
    }else{
         layer.closeAll('tips');
    }
}

function get_pte(){
    var price=$("input[name=pte]").val();
    var match=/^\d+|^\d+\.\d+|^\d+\.|^\d+\.\d+$/;
    if(!match.test(price)){
        layer.tips("<%'格式不正确,请正确填写税率'|L%>",$("input[name=pte]"), {
            tips:[1, '#A83A3A'],
            time:8000
        });
    }else{
         layer.closeAll('tips');
    }
}

/**
 * amp&emp进行切换
 */
/*
$("a.amp").on("click",function(){
    $("input[name=type]").val("amp");
    $("a.amp").addClass("active");
    $("a.emp").removeClass("active");
    $("#timedate").removeClass("none");
    $("select[name=lv1] option").eq(0).attr("disabled");
    $(function(){	
    $.ajax({
            url:'?m=account&a=getjson',
            dataType:'json',
            success:function(res){
                $("input[name=ep_id]").val(res.citylist[0].ag_number);
                $("#city_5").citySelect({
                            url:res,
                            prov:res.citylist[0].ag_number,
                            city:"",
                            dist:"",
                            nodata:"none"
                    });
            }
         });
    });
        send();
});
$("a.emp").on("click",function(){
    $("input[name=type]").val("emp");
    $("a.emp").addClass("active");
    $("a.amp").removeClass("active");
    $("#timedate").addClass("none");
      $(function(){	
    $.ajax({
            url:'?m=account&a=getjson_ep',
            dataType:'json',
            success:function(res){
                $("input[name=ep_id]").val(res.citylist[0].ag_number);
                $("#city_5").citySelect({
                            url:res,
                            prov:res.citylist[0].ag_number,
                            city:"",
                            dist:"",
                            nodata:"none"
                    });
            }
         });
    });
    send();
});
*/
/*
$("a.export").click(function(){
    var ep_id = $("input[name=ep_id]").val();
    var start = $("input[name=start]").val();
    var end = $("input[name=end]").val();
    var type = $("input[name=type]").val();
    var open_bank = $("input[name=open_bank]").val();
    var open_bank_account = $("input[name=open_bank_account]").val();
    var other_price = $("input[name=other_price]").val();
    var pre_total = $("input[name=pre_total]").val();
    var remarks = $("input[name=remarks]").val();
    var pte = $("input[name=pte]").val();
    var total = $("input[name=total]").val();
    
    var src="?m=account&a=export_amp&ep_id="+ep_id+"&start="+start+"&type="+type+"&open_bank="+open_bank+"&open_bank_account="+open_bank_account+"&other_price="+other_price+"&pre_total="+pre_total+"&remarks="+remarks+"&pte="+pte+"&total="+total;
    $("#ifr").attr("src",src);
});*/

$("a.export").click(function(){
    var ep_id = $("input[name=ep_id]").val();
    var start = $("input[name=start]").val();
    var end = $("input[name=end]").val();
    var type = $("input[name=type]").val();
    var open_bank = $("input[name=open_bank]").val();
    var open_bank_account = $("input[name=open_bank_account]").val();
    var other_price = $("input[name=other_price]").val();
    var pre_total = $("input[name=pre_total]").val();
    var remarks = $("input[name=remarks]").val();
    var pte = $("input[name=pte]").val();
    var total = $("input[name=total]").val();
    
    var src="?m=account&a=export_amp&ep_id="+ep_id+"&start="+start+"&type="+type+"&open_bank="+open_bank+"&open_bank_account="+open_bank_account+"&other_price="+other_price+"&pre_total="+pre_total+"&remarks="+remarks+"&pte="+pte+"&total="+total;
    $("#ifr").attr("src",src);
});



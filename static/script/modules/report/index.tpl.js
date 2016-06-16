/*
Ajax 三级省市联动
日期：2012-7-18

settings 参数说明
-----
url:省市数据josn文件路径
prov:默认省份
city:默认城市
dist:默认地区（县）
nodata:无数据状态
required:必选项
------------------------------ */
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
			temp_html=select_prehtml;
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


$(function(){	
	$.ajax({
		url:'?m=report&a=getjson',
		dataType:'json',
		success:function(res){
			$("#city_5").citySelect({
					url:res,
					prov:"",
					city:"",
					dist:"",
					nodata:"none"
				});
		}
	});
});

//折线病状数据图

    // in order to set theme for a chart, all you need to include theme file
        // located in amcharts/themes folder and set theme property for the chart.

        var chart1;
        var chart2;
        var data=[{
                    "date": 2005,
                        "income": 1,
                        "expenses": 1
                }, {
                    "date": 2006,
                        "income": 10,
                        "expenses": 11
                }, {
                    "date": 2007,
                        "income": 30.1,
                        "expenses": 23.9
                }, {
                    "date": 2008,
                        //"income": 29.5,
                        "expenses": 25.1
                }, {
                    "date": 2009,
                        //"income": 24.6,
                        "expenses": 250
                }, {
                    "date": 2009,
                        //"income": 24.6,
                        "expenses": 250
                }, {
                    "date": 2009,
                        //"income": 24.6,
                        "expenses": 250
                }, {
                    "date": 2009,
                        //"income": 24.6,
                        "expenses": 250
                }];

        makeCharts("light", "#E5E5E5");

        // Theme can only be applied when creating chart instance - this means
        // that if you need to change theme at run time, youhave to create whole
        // chart object once again.

        function makeCharts(theme, bgColor, bgImage){

            if(chart1){
                chart1.clear();
            }
            if(chart2){
                chart2.clear();
            }

            // background
            if(document.body){
                document.body.style.backgroundColor = bgColor;
                document.body.style.backgroundImage = "url(" + bgImage + ")";
            }

            // column chart
            chart1 = AmCharts.makeChart("chartdiv1", {
                type: "serial",
                theme:theme,
                dataProvider: data,//折线数据
                categoryField: "date",
                startDuration: 1,

                categoryAxis: {
                    gridPosition: "start"
                },
                valueAxes: [{
                    title: "数据统计"
                }],
                graphs: [{
                    type: "column",
                    title: "Income",
                    valueField: "income",
                    lineAlpha: 0,
                    fillAlphas: 0.8,
                    balloonText: "<b>[[value]]</b>"
                }, {
                    type: "line",
                    title: "Expenses",
                    valueField: "expenses",
                    lineThickness: 2,
                    fillAlphas: 0,
                    bullet: "round",
                    balloonText: "<b>[[value]]</b>"
                }],
                legend: {
                    useGraphSettings: true
                }

            });

            // pie chart
            chart2 = AmCharts.makeChart("chartdiv2", {
                type: "pie",
                theme: theme,
                dataProvider: [{
                    "country": "Czech Republic",
                        "litres": 156.9
                }, {
                    "country": "Ireland",
                        "litres": 131.1
                }, {
                    "country": "Germany",
                        "litres": 115.8
                }, {
                    "country": "Australia",
                        "litres": 109.9
                }, {
                    "country": "Austria",
                        "litres": 108.3
                }, {
                    "country": "UK",
                        "litres": 65
                }, {
                    "country": "Belgium",
                        "litres": 50
                }],
                titleField: "country",
                valueField: "litres",
                balloonText: "[[title]]<br><b>[[value]]</b> ([[percents]]%)",
                legend: {
                    align: "center",
                    markerType: "circle"
                }
            });

        }


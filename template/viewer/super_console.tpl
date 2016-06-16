<style>
        div.nav a{
                background: #00bbf1;
                display: inline-block;
                width: 120px;
                height: 80px;
                color: #FFF;
                padding: 4px;
                line-height: 1.4em;
                cursor: pointer;
                border: 2px solid #00bbf1;
                float: left;
                margin-right: 10px;
        }
        div.nav a:hover{
                background: #FFF;
                color: #00bbf1;
        }

</style>
<h2 class="title">超级控制台</h2>
<div class="nav">
        <a data="3" class="super">一键企业可用</a>
        <a data="4" class="super">一键企业失败</a>
        <a data="1" class="super">一键刷新设备正常</a>
        <a data="2" class="super">一键设备异常</a>
        <a data="5" class="super">一键添加授权设备</a>
</div>
<script src="script/common.js" type="text/javascript"></script>

<script>
        $(".super").bind("click", function() {
                var data = $(this).attr("data");
                var url = "?p==modules/api/action/superconsole&handle=" + data;
                $.ajax({
                        url: url,
                        dataType: 'JSON',
                        success: function(result) {
                                notice(result.msg);
                        }
                });
        });
        (function() {
                $('nav a.console').addClass('active').siblings().addClass("black");
        })();
</script>

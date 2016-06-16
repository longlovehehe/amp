<!DOCTYPE html>
{strip}
        <html>
                <head>
                        <meta charset="UTF-8">
                        <meta name="renderer" content="webkit">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                        <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        {'reset|p404'|style}
                        <title>讯息</title>
                </head>
                <body class="p404">
                        <div class="content">
                                <div class="show pstyle{"7"|modrand}"></div>
                                <div class="message">{$title|default: "提示讯息"}</div>
                                <div class="info">详细信息：{$msg}</div>
                                <hr />
                                <div class="toolbar">
                                        <a onclick="window.location.reload()">刷新</a>
                                        <a href="?m=login">重新登录</a>
                                </div>
                        </div>
                </body>
        </html>
{/strip}
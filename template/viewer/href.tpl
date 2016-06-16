<!DOCTYPE html>
{strip}
<html>
    <head>
        <meta charset="UTF-8">
        <title>{"页面转向中"|L}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon"/>
    </head>
    <body>
        <p>{"{$msg}"|L}<br />{"页面转向中，请稍候。如果系统没有响应请"|L}<a href="{$href}">{"点击跳转"|L}</a></p>
        <script>
            setTimeout(function () {
                window.location.href = "{$href}";
            }, 999);
        </script>
    </body>
</html>
{/strip}
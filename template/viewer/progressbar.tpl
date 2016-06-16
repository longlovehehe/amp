<!DOCTYPE html>
{strip}
    <html>
        <head>
            <meta charset="UTF-8">
            <title>{$data.title}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="static/style/common.css" rel="stylesheet" type="text/css" />
            <link href="static/style/skin.css" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <h2 class="title">{$data.h2}</h2>
            <form method="get">
                <input autocomplete="off"  name="enterprise" />
                <input autocomplete="off"  name="action" />
                <input autocomplete="off"  name="e_id" />
                <input autocomplete="off"  name="total" />
                <input autocomplete="off"  name="step" />
                
                <input autocomplete="off"  name="u_type" />
                <input autocomplete="off"  name="u_auto_pre" />
                <input autocomplete="off"  name="u_auto_number" />
                <input autocomplete="off"  name="u_auto_pwd" />
                <input autocomplete="off"  name="u_default_pg" />
                <input autocomplete="off"  name="u_product_id" />
                <input autocomplete="off"  name="u_ug_id" />
            </form>
            <progress max="{$data.max}" value="{$data.value}" class="progress">
                <span id="objprogress">85</span>%
            </progress>
        </body>
    </html>
{/strip}
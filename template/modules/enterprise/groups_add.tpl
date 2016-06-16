{strip}

<h2 class="title">{"{$title}"|L}</h2>

<form id="form" class="base mrbt10" action="?modules=enterprise&action=groups_save">

    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />

    <input autocomplete="off"  value="{$data.do}" name="do" type="hidden" />

    <input autocomplete="off"  value="{$data.pg_number}" name="pg_number" type="hidden" />

    <div class="block ">

        <label class="title">{"群组号码"|L}：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_number}" name="pg_number" type="text" required="true" digits="true" {if $data.do != 'edit'}range="[00000,09999]"{/if}  {if $data.do == 'edit'}readonly{/if} />

    </div>

    <div class="block">

        <label class="title">{"群组名称"|L}：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_name}" chinese="true" name="pg_name" type="text" required="true" />

    </div>

    <div class="block">

        <label class="title">{"群组级别"|L}：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_level|default:7}" range='[1,7]' name="pg_level" type="text" required="true" digits="true" />

    </div>

    <div class="block">

        <label class="title">{"组空闲超时"|L}（{"秒"|L}）：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_grp_idle|default:30}" range='[1,1800000]' name="pg_grp_idle" type="text" required="true" digits="true" />

    </div>

    <div class="block">

        <label class="title">{"话权空闲超时"|L}（{"秒"|L}）：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_speak_idle|default:10}" range='[1,1800000]' name="pg_speak_idle" type="text" required="true" digits="true" />

    </div>

    <div class="block">

        <label class="title">{"话权总超时"|L}（{"秒"|L}）：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_speak_total|default:120}" range='[1,1800000]' name="pg_speak_total" type="text" required="true" digits="true" />

    </div>





    <div class="block">

        <label class="title">{"排队人数限制"|L}：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_queue_len|default:5}" range='[0,1800000]' name="pg_queue_len" type="text" required="true" digits="true" />

    </div>

    <div class="block none">

        <label class="title">{"无线终端状态上报周期"|L}（{"秒"|L}）：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_chk_stat_int|default:1800}" range='[0,1800000]' name="pg_chk_stat_int" type="text" required="true" digits="true" />

    </div>

    <div class="block none">

        <label class="title">{"缓冲区包个数"|L}：</label>

        <input autocomplete="off"   maxlength="32" value="{$data.pg_buf_size|default:0}" range='[0,1800000]' name="pg_buf_size" type="text" required="true" digits="true" />

    </div>



    <div class="block radio none" value="{$data.pg_record_mode|default:0}">

        <label class="title">{"录音模式"|L}：</label>



        <label>

            <input autocomplete="off"  value="0" name="pg_record_mode" type="radio"  />

            <span>{"对讲频道全程录音"|L}</span>

        </label>



        <label>

            <input autocomplete="off"  value="1" name="pg_record_mode" type="radio"  />

            <span>{"根据话权方的录音标志录音"|L}</span>

        </label>

        <label>

            <input autocomplete="off"  value="2" name="pg_record_mode" type="radio" checked="checked" />

            <span>{"不录音"|L}</span>

        </label>

    </div>



    <div class="block checkbox none"  value="{$data.pg_hangup|default:0}">

        <label class="title"></label>

        <label class="title">

            <div style="width:400px;">
                <input maxlength="32" name="pg_hangup" type="checkbox"/>
                <span>{"主叫挂断对讲组权限"|L}</span>
            </div>

        </label>

    </div>



    <div class="buttons mrtop40">

        <a form="form" goto="?m=enterprise&a=groups&e_id={$data.e_id}" class="ajaxpost button normal" id='save'>{"保存"|L}</a>

        <a class="goback button">{"取消"|L}</a>

    </div>

</form>

{/strip}
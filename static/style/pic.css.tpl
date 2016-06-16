<%foreach name=list item=item from=$list%>
._<%$item.name%>{
        background-image: url("<%$item.url%>") !important;
}
<%/foreach%>

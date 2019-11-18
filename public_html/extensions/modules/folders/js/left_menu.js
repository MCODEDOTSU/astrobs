var left_menu_nav = '#left_menu_nav';
var left_menu_content = '#left_menu_content';
var menu = 132;

jQuery(document).ready(function(){
    LeftMenuGetAjax();
});

function LeftMenuGetAjax(folder_id)
{
    $.post('http://'+window.location.hostname+'/left_menu/left_menu/get', {folder:folder_id}, function(data){
        $(left_menu_content).html(data);
    });
}
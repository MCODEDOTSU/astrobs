(function($) {

  $.placeItemActions = function(data) {}
  $.fn.placeItemActions = function(settings) {$.placeItemActions.previous(this);}
  
  $.extend($.placeItemActions, {

    settings: {
        item: 'div.place_item',
        attr: 'item',
        container: 'li',
        html: '<div class="item_actions"></div>',
        icon: {
        	edit: '<img src="http://'+window.location.hostname+'/cms_icons/textfield_key.png" />',
            rename: '<img src="http://'+window.location.hostname+'/cms_icons/textfield_rename.png" />',
            remove: '<img src="http://'+window.location.hostname+'/cms_icons/textfield_delete.png" />'
        }
    },

    loading: function(){loading(true);},
    previous: function(e){
        $.placeItemActions.loading();

        $(e).each(function(){

            var item = $(this);
            var dataString = $('a', $(item)).attr($.placeItemActions.settings.attr); // string from attribute "item"
            var dataArray = dataString.split(':'); // array from attribute "item"
            var container = $(item).parents($.placeItemActions.settings.container + ':eq(0)'); // li

            $(container).hover(
                function (){$(".item_actions", $(this)).show();},
                function (){$(".item_actions", $(this)).hide();}
            );

            $('.item_actions', $(container)).remove();
            var div = $($.placeItemActions.settings.html).prependTo($(container)).hide();
			if (dataArray[0] == 'f') {
				$('<a href="#" title="Редактировать">'+$.placeItemActions.settings.icon.edit+'</a>').appendTo($(div)).click(function(){ $.placeItemActions.edit(dataArray); return false;});
			}
            $('<a href="#" title="Переименовать">'+$.placeItemActions.settings.icon.rename+'</a>').appendTo($(div)).click(function(){ $.placeItemActions.rename(dataArray, item, container); return false;});
            $('<a href="#" title="Удалить">'+$.placeItemActions.settings.icon.remove+'</a>').appendTo($(div)).click(function(){ $.placeItemActions.remove(dataArray, container); return false;});
            
        });
    },
    
    rename: function(params, item, container){
        
        $(document).click();
        $(container).click(function(){return false;})
        
        var img = $.placeItemActions.getIconFromItem($(item));
        
        $(item).hide();
        
        var form = $('<div class="place_item" id="container"></div>').appendTo($(container));

        $(document).unbind('click').click(function(){
            $(form).remove();
            $(item).show();
        });


        $(img).appendTo($(form));
        $('<input type="text" name="title" value="" style="width:200px;"/>').appendTo($(form)).val($(item).text());
        $('<input type="button" value="Сохранить" class="container_btn" />').appendTo($(form)).click(function(){
            $('a', $(item)).text(
                $('input[name=title]',$(form)).val()
            ).prepend(img);
                
            $(document).click();
            $.placeItemActions.ajax('http://'+window.location.hostname+'/admin/place/content/rename', {
                id:   params[1],
                type: params[0],
                title: $('input[name=title]',$(form)).val()
            });
            //$.post ('http://'+document.location.hostname+'/admin/place/place/gettree', {lang: '1'}, function (data) {
            	//alert (data);
				//$('td#place_tree').html (data);
				/*tinyMCE.init({
					convert_urls : false
				});*/
        	//});
        });
        
    },

    edit: function(params){
    	document.location.href ='http://'+window.location.hostname+'/admin/folders/folders/edit/'+params[1];
    },

    remove: function(params, container){
        $.placeItemActions.ajax('http://'+window.location.hostname+'/admin/place/content/remove', {
            id:   params[1],
            type: params[0]
        });

        $(container).remove();
        /*$.post ('http://'+document.location.hostname+'/admin/place/place/gettree', {lang: '1'}, function (data) {
			$('td#place_tree').html (data);
        });*/
    },

    ajax: function(url, data){
        $.post(url,data);
    },

    getIconFromItem:  function(item){
        return $('img', $(item)).clone();
    }
    
  });

  function loading(settings) {
    if(settings) $.extend($.placeItemActions.settings, settings);
  }
  
  jQuery(document).ready(function(){
    $('body').ajaxComplete(function() {
        $('div.place_item', $('#place_content')).placeItemActions();
    });

  });

})(jQuery);

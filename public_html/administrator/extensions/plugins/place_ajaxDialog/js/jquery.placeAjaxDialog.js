(function($) {

  var dialog;
  
  $.placeAjaxDialog = function(data) {}
  $.fn.placeAjaxDialog = function(settings) {return this.each(function() {
          jQuery(this).unbind().click(function(){$.placeAjaxDialog.clickHandler(this);/*alert ('sadf');*/return false;})
  });}
  $.extend($.placeAjaxDialog, {

    settings: {
        autoOpen: true,
        bgiframe: true,
        buttons: {},
        closeOnEscape: true,
        dialogClass: '',
        draggable: true,
        height: 'auto',
        hide: null,
        maxHeight: false,
        maxWidth: false,
        minHeight: 150,
        minWidth: 150,
        modal: true,
        position: 'center',
        resizable: false,
        show: null,
        stack: true,
        title: '',
        width: 'auto',
        zIndex: 1000,
        beforeclose: function(){
            $('#dialog').remove();
            $(dialog).dialog('destroy');
            $('body > div[rel=content]').remove();
        }
    },
    
    loading: function(e){loading(true, e);},
    clickHandler: function(e){$.placeAjaxDialog.loading(e);},
    ajax: function(url, data){$.post(url,data,function(data){$.placeAjaxDialog.parse(data);});},
    show:function(){
        
        $.placeAjaxDialog.settings.title = $('#dialog > title').text();
        dialog = $('#dialog > div[rel=content]').dialog($.placeAjaxDialog.settings);
    },
    parse:function(data){
        
        $('<div id="dialog"></div>').appendTo($('body'));
        $('#dialog').html(data);
        
        //$.placeAjaxDialog.show();
    }
    
  });
  
  function loading(settings, el) {
    if(settings) $.extend($.placeAjaxDialog.settings, settings);

    var item = $(el);
    
    if($(item).attr('href')){
        var attr = $(item).attr('item');
        var rel = attr.split(':');
        document.location.href=  $(item).attr('href') + '/'+rel[1];
        $.placeAjaxDialog.ajax($(item).attr('href'),{
            id: rel[1]
        });
    }
  }

  jQuery(document).ready(function(){
    $('a[rel*=dialog]').placeAjaxDialog();
    $('body').ajaxComplete(function() {
        $('a[rel*=dialog]').placeAjaxDialog();
    });

  });
  
})(jQuery);

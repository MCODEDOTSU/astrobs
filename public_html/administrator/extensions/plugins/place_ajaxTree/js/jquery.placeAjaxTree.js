(function($) {

  $.placeAjaxTree = function(data) {}
  $.fn.placeAjaxTree = function(settings) {
      loading(settings);if($.placeAjaxTree.settings.preload){$.placeAjaxTree.preload();}
      /*var ttt = '';
      for (var i in settings) {
		ttt += i+'\n';
      }
      //alert (ttt);
      alert (settings.preload);*/
      return this.each(function() {
          if(jQuery(this).find('a').attr('rel') != 'dialog') {
          	//alert (/*$(this).html ()*/$.placeAjaxTree.preload.id);
            jQuery(this).unbind('click').click(function(){$.placeAjaxTree.previous(this);/*alert ($(this).find ('a').attr ('item'));*/return false;});
          }
      });
  }
  $.extend($.placeAjaxTree, {

    settings: {
        url: 'http://'+window.location.hostname+'/admin/place/content/folder',
        root: 1,
        preload: true,
        attr: 'item'
    },

    loading: function(e){loading(true, e);},
    previous: function(e){$.placeAjaxTree.loading(e);},
    ajax: function(url, data){$.post(url,data,function(response){$.placeAjaxTree.parse(response);});},
    parse: function(data){
        $('#place_content').html(data);
    },
    preload: function(){
        $.placeAjaxTree.ajax($.placeAjaxTree.settings.url,{
            id:$.placeAjaxTree.settings.root,
            item: 'f'
        });
    }
    

  });

  function loading(settings, el) {
    if(settings) $.extend($.placeAjaxTree.settings, settings);
    if(!el) return false;

    var item = $('a', $(el)).get();
    var dataString = $(item).attr($.placeAjaxTree.settings.attr)
    var dataArray = dataString.split(':');

    switch(dataArray[0])
    {
        case 'f':
        case 'c':
            $.placeAjaxTree.ajax($(item).attr('href'),{
                id:dataArray[1],
                item:dataArray[0]
            });
            break;
    }
    
  }
  jQuery(document).ready(function(){
    //$('div.place_item').placeAjaxTree();
    $('body').ajaxComplete(function() {
        $('div.place_item').placeAjaxTree({preload:false});
    });

  });
  
})(jQuery);

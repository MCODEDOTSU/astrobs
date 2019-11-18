/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

(function($) {

  $.placeForm = function(data) {}
  $.fn.placeForm = function(settings) {return this.each(function() {jQuery(this).click(function(){$.placeForm.clickHandler(this);return false;})});}

  $.extend($.placeForm, {
    settings: {
        url: 'http://'+window.location.hostname+'/admin/place/content/create'
    },

    loading: function(){loading(true);},
    clickHandler: function(e){$.placeForm.loading();clickHandler(e);},
    ajax: function(url, data){$.post(url,data,function(response){$.placeForm.parse(response);});},
    show:function(){

        $('#placeNewItem').show().removeAttr('id');
    },
    parse:function(response){
        $('<li id="placeNewItem"></li>').prependTo($('#place_content').find('ul')).hide().html(response);
        $.placeForm.show();
    }

  });

  function loading(settings) {
      if(settings) $.extend($.placeForm.settings, settings);
  }

  function clickHandler(e)
  {
      $('#place_form').show();
      
      $(document).click(function(){$('#place_form').hide()});

      $('#place_form').click(function(){return false;})

      $('input[type=submit]', $('#place_form')).unbind('click').click(function(){
         $('form', $('#place_form')).submit();
         $('#place_form').hide();
      });

      $('form', $('#place_form')).attr('action', $.placeForm.settings.url);
      $('form', $('#place_form')).ajaxForm({
          success: $.placeForm.parse,
          resetForm: true
      });

  }
  
})(jQuery);

jQuery(document).ready(function(){
    $('#placeFormBtn').placeForm();
});


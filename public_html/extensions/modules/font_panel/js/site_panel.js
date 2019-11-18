var site_panel = {
	set_color: function (val) {
		switch (val) {
			case 'site_color1':
				$('body').removeClass('color3 color2').addClass('color1');
				break;
			case 'site_color2':
				$('body').removeClass('color3 color1').addClass('color2');
				break;
			case 'site_color3':
				$('body').removeClass('color1 color2').addClass('color3');
				break;
		}
		$.post('http://'+window.location.hostname+'/font_panel/panel/color', {color:val});
	},
	set_font: function (val) {
		val = val.substring(0, 10);
		switch (val) {
			case 'site_font1':
				$('body').css('font-size', '100%');
				break;
			case 'site_font2':
				$('body').css('font-size', '120%');
				break;
			case 'site_font3':
				$('body').css('font-size', '140%');
				break;
		}
		
		$.post('http://'+window.location.hostname+'/font_panel/panel/font', {font:val});
		
	}
};

$(document).ready(function(){
	$('.site_colors a').click(function(){
		site_panel.set_color($(this).attr('class'));
	});
	$('.site_fonts a').click(function(){
		$('.site_fonts a').removeClass('active_f');
		$(this).addClass('active_f');
		site_panel.set_font($(this).attr('class'));
	});

});

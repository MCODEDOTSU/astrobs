var mouseRule = {
	link: 'http://' + document.location.hostname + '/uploads/folders/img',
	mouseOver: function (id, ext) {
		$('#timg_' + id).attr ('src', this.link + '3_' + id + '.' + ext);
	},
	mouseUp: function (id, ext) {
		$('#timg_' + id).attr ('src', this.link + '2_' + id + '.' + ext);
	}
}
/*
$(document).ready (function () {
	$('li.tourMOver a').mouseover (function () {
		$(this).find ('img').attr ('src', mouseRule.getLink ('over'));
	});
});
*/

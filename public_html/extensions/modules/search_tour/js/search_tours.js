var search_tours = {
	loading: function () {
		if (!$('#cttype').attr ('checked')) $('#dttype').css ('display', 'none');
		if (!$('#ctstype').attr ('checked')) $('#dtstype').css ('display', 'none');
		if (!$('#ctname').attr ('checked')) $('#dtname').css ('display', 'none');
	},
	display: function (id) {
		var form_id;
		id = '#' + id;
		switch (id) {
			case '#cttype':
				form_id = '#dttype';
				break;
			case '#ctstype':
				form_id = '#dtstype';
				break;
			case '#ctname':
				form_id = '#dtname';
				break;
		}
		if ($(id).attr ('checked')) {
			$(form_id).css ('display', 'block');
		} else {
			$(form_id).css ('display', 'none');
		}
	}
}
$(document).ready (function () {
	search_tours.loading ();
	$('#cttype').click (function () {
		search_tours.display (this.id);
	})
	$('#ctstype').click (function () {
		search_tours.display (this.id);
	})
	$('#ctname').click (function () {
		search_tours.display (this.id);
	})
});

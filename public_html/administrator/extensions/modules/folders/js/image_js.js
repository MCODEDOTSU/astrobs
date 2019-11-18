var imgJS = {
	deleted: [],
	setDeleted: function (id) {
		var size = this.deleted.length;
		this.deleted[size] = id;
		$('#' + id).html ('<input type="file" name="' + id + '">');
		$('#form_delete').attr ('value', JSON.stringify (this.deleted));
	}
}

$(document).ready (function (){
	$('a.clicker').click (function () {
		var lid = $(this).attr('id');
		var iid = lid.substring (1, lid.length);
		imgJS.setDeleted (iid);
	});
})

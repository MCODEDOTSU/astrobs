var tour_obj = {
	openTabType: false,
	segid: 0,
	errors: {},
	ord: function () {
		switch ($('#order').css ('display')) {
			case 'block':
				$('#order').css ('display', 'none');
				$('#linkOpen').text ('Заказать тур');
				break;
			case 'none':
				$('#order').css ('display', 'block')
				$('#linkOpen').text ('Скрыть заказ туров');
				break;
			default:
				break;
		}
	}, 
	checkFields: function () {
		var errorLog = [], textError = '', result = false;
		if ($('#fname').attr ('value').length < 5) errorLog[errorLog.length] = this.errors.error_name;
		if (!$('#fname').attr ('value').match (/^[a-zA-Zа-яА-Я\s]*$/)) errorLog[errorLog.length] = this.errors.error_name_2;
		if (!$('#femail').attr ('value').match (/^[a-zA-Z0-9\._\-]+@[a-zA-Z0-9\._\-]+\.[a-zA-Z]+$/)) errorLog[errorLog.length] = this.errors.error_email;
		if (!$('#fphone').attr ('value').match (/^\+{0,1}[0-9]{11}$/)) errorLog[errorLog.length] = this.errors.error_phone;
		if ($('#newTour').attr ('value') == 1) {
			if (!$('#trName').attr ('value').match (/^[0-9]+$/)) errorLog[errorLog.length] = this.errors.error_tour;
		}
		for (var i = 0; i < errorLog.length; i++) {
			textError += errorLog[i] + "\n";
		}
		if (errorLog.length > 0 ) {
			alert (textError);
			result = false;
		} else {
			result = true;
		}
		return result;
	},
	openTourTypes: function () {
		if (this.openTabType) {
			$('#selTour').text ('Выбрать другой тур');
			$('#tourSels').css ('display', 'none');
			$('#newTour').attr ('value', '0');
			this.openTabType = false;
		} else {
			$('#selTour').text ('Оставить текущий тур');
			$('#tourSels').css ('display', 'block');
			$('#newTour').attr ('value', '1');
			this.changeType ();
			this.openTabType = true;
		}
	},
	changeType: function () {
		var segm = this.getSegment ();
		$.post ('http://' + document.location.hostname + '/cattours/tours/gettypes', {segment: segm}, function (data) {
			$('#trType').html (data);
		})
		this.changeTour (segm);
	},
	changeTour: function (segm) {
		$.post ('http://' + document.location.hostname + '/cattours/tours/gettours', {segment: segm}, function (data) {
			$('#trName').html (data);
		})
	},
	getSegment: function () {
		var result, url = location.href, segs = [];
		if (this.segid == 0) {
			segs = url.match (/[0-9]+/g);
			this.segid = segs[segs.length - 1];
		}
		return this.segid;
	},
	setChangeTour: function (item) {
		var id;
		$.post ('http://' + document.location.hostname + '/cattours/tours/gettourid', {segment: item}, function (data) {
			obj.setSegment (data);
			obj.changeTour (obj.getSegment ());
		});
	},
	setSegment: function (item) {
		this.segid = item;
	},
	getTextErrors: function () {
		$.post ('http://' + document.location.hostname + '/tour_ord/tour_ord/gettexterrors', {request: true}, function (data) {
			tour_obj.setErrorMessages (data);
		}, 'json')
	},
	setErrorMessages: function (item) {
		this.errors = item;
	}
}

$(document).ready (function () {
	tour_obj.getTextErrors ();
});

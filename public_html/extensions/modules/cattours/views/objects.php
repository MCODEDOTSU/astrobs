<script type="text/javascript">
	var obj = {
		openTabType: false,
		segid: 0,
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
			if ($('#fname').attr ('value').length < 5) errorLog[errorLog.length] = 'Вы ввели слишком мало символов в ФИО';
			if (!$('#fname').attr ('value').match (/^[a-zA-Zа-яА-Я]*$/)) errorLog[errorLog.length] = 'Вы ввели недопустимые символы в ФИО';
			if (!$('#femail').attr ('value').match (/^[a-zA-Z0-9\._\-]+@[a-zA-Z0-9\._\-]+\.[a-zA-Z]+$/)) errorLog[errorLog.length] = 'Вы ввели некорректный EMail';
			if (!$('#fphone').attr ('value').match (/^\+{0,1}[0-9]{11}$/)) errorLog[errorLog.length] = 'Вы ввели некорректный номер телефона';
			if ($('#newTour').attr ('value') == 1) {
				if (!$('#trName').attr ('value').match (/^[0-9]+$/)) errorLog[errorLog.length] = 'Отсутствует тур';
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
		}
	}
</script>

<div class="content_desc">
<h1>{name}</h1>
{desc}
{objs}
	{link}
	<br>
{/objs}
<br>
<a id="linkOpen" href="javascript: return false;" onclick="obj.ord ()">Заказать тур</a>
<div id="order" style="display: none">
	{fo}
		 <label for="fname">Ф.И.О.:</label>
		 {fname}
		 <br>
		 <label for="femail">Email:</label>
		 {femail}
		 <br>
		 <label for="fphone">Контактный телефон:</label>
		 {fphone}
		 <br>
		 Тур: {ftname} <a id="selTour" href="javascript: return false;" onclick="obj.openTourTypes ();">Выбрать другой тур</a><br> 
		 <div id="tourSels" style="display: none">
		 Тип тура: <select id="trType" style="display: block" onchange="obj.setChangeTour (this.value);"></select> 
		 Тур: <select id="trName" name="trName" style="display: block"></select>
		 </div>
		 <br>
		 <label for="fnums">Количество человек в группе:</label>
		 {fnums}
		 <br>
		 Период:
		 <br>
		 С {fdays} {fmoons}
		 <br>
		 ПО {fdaye} {fmoone}
		 <br>
		 <label for="fneeds">Пожелания:</label>
		 {fneeds}
		 <br>
		 <label for="fnums">Прочая информация:</label>
		 {fmore}
		 <br>
		 {ftid}
		 <input type="hidden" name="newTour" id="newTour" value="0">
		 {fbut}
		 <br>
	{fc}
</div>
</div>

<script type="text/javascript">
	/*var obj = {
		checkTour: function () {
			//alert($('#is_tour').attr ('checked'));
			if ($('#is_tour').attr ('checked') == true) {
				$('#selTour').css ('display', 'block');
				$('#saveTour').attr ('value', 'yes');

			} else {
				$('#selTour').css ('display', 'none');
				$('#saveTour').attr ('value', 'no');

			}
		}
	}*/
</script>

<div class="cms_title"><?=_module_icon('anchor')?>Создание / Редактирование ссылки</div>


<div class="cms_content">
	{form_open}
	{file}

		    <label for="title">Заголовок</label>
		    {title}

		    <label for="title">URL</label>
		    {url}
			<br>
			<label for="is_tour">Сделать ссылкой на вид тура</label>
			{is_tour}
			<!--<label for="selTour">Выбрать тур</label>-->
			{selTour}
			<input type="hidden" name="saveTour" id="saveTour" value="no">
		    <input type="submit" value="Сохранить">

	{form_close}

</div>

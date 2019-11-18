<script type="text/javascript">
	var scr = {
		hide: true,
		setHide: function () {
			if (this.hide) {
				$('#hideOrd').css ('display', 'block');
				$('#butOrd').text ('Скрыть');
				this.hide = false;
			} else {
				$('#hideOrd').css ('display', 'none');
				$('#butOrd').text ('Заказать тур');
				this.hide = true;
			}
		},
		checkF: function () {
			var errorLog = [], errors = '';
			if ($('#fname').attr ('value').length < 4) errorLog[errorLog.length] = "Введите имя";
			if ($('#fphone').attr ('value').length < 6) errorLog[errorLog.length] = "Введите номер телефона";
			
			if (errorLog.length > 0) {
				for (var i = 0; i < errorLog.length; i++) {
					errors += errorLog[i] + "\n";
				}
				alert (errors);
				return false;
			} else {
				return true;
			}
		}
	};
</script>

<div class="content_desc">
<h1>{name}</h1>
{desc}

Ссылки:
<br>
{arr}
	{title}
	<br>
{/arr}
<br>
<a id="butOrd" href="javascript: return false;" onclick="return scr.setHide ()">Заказать тур</a>
<div id="hideOrd" style="display: none;">
	{fo}
		{fname}
		<br>
		{fphone}
		<br>
		{femail}
		<br>
		{ffrom}
		<br>
		{fdate}
		{fid}
		<br>
		{fs}
		<br>

	{fc}
</div>
</div>
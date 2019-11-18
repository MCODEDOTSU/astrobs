<script type="text/javascript">
	var search_obj = {
		search_submit: function () {
			if ($('#search_text').attr('value') == '') return false;
			$('#search form').submit();
		}
	}
</script>

<div id="search">
	
    {form_open}
        <label>
		    Поиск:<br />
		    {search}
        </label>
        {submit}
    {form_close}
	
</div>

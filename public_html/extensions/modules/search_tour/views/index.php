<div class="content_desc">
	{breadCrumbs}
	<h1>{search_tour_text}</h1>
	{form_open}
		<label for="cttype">{text_search_type_tour}</label> {check_tour_type}
		<br />
		<label for="ctstype">{text_search_sub_type_tour}</label> {check_tour_sub_type}
		<br />
		<label for="ctname">{text_search_name_tour}</label> {check_tour_name}
		<hr />
		<div id="dttype" style="display: block;"><label for="fttype">{text_type_tour}:</label> {form_tour_type}</div>
		<div id="dtstype" style="display: block;"><label for="ftstype">{text_sub_type_tour}:</label> {form_tour_sub_type}</div>
		<div id="dtname" style="display: block;"><label for="ftname">{text_name_tour}:</label> {form_tour_name}</div>
		{form_submit}
	{form_close}
</div>

<h1 align="center">Назначить туры</h1>

{form_open}
	<table>
		<tr><th>Директория</th><th>Сделать туром</th><th>Редактировать описание</th></tr>
		{arr}
			<tr><td>{title}</td><td>{check}</td><td>{edit_desc}</td></tr>
		{/arr}
	</table>
	{form_submit}
{form_close}
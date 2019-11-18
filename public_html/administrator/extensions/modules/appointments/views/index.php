<script type="text/javascript">
	$(document).ready(function(){
		$("table[rel*=tables]").dataTable({
			"aaSorting": [[4,'desc']]
		});
	});
</script>


<div id="cms_bar"></div>

<table cellspacing="0" cellpadding="0" border="0" rel="tables">

    <thead>
        <tr>
            <th>Номер</th>
            <th>Ф.И.О.</th>
            <th>E-mail</th>
            <th>Телефон</th>
            <th>Время приема</th>
            <th>Комментарий</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {appointments}
            <tr style="background: {state};">
                <td>{id}</td>
                <td>{author}</td>
                <td>{email}</td>
                <td>{phone}</td>
                <td>{time}</td>
                <td>{description}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/appointments}
    </tbody>

</table>

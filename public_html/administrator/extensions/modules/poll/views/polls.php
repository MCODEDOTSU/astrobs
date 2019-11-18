

<table cellspacing="0" cellpadding="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Наименование</th>
            <th>Краткое описание</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {polls}
            <tr {archive}>
                <td>{id}</td>
                <td>{title}</td>
                <td>{desc}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/polls}
    </tbody>
</table>
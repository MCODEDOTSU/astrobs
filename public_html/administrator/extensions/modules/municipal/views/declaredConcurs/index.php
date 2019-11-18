<table border="0" cellpadding="0" cellspacing="0" rel="datatables">
    <thead>
        <tr>
            <th>Id</th>
            <th>Номер</th>
            <th>Название</th>
            <th>Раздел</th>
            <th>Дата проведения</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {concurs}
        <tr>
            <td>{id}</td>
            <td>{number}</td>
            <td>{title}</td>
            <td>{section}</td>
            <td>{dataToOpen}</td>
            <td class="cms_td_center">{actions}</td>
        </tr>
        {/concurs}
    </tbody>
</table>

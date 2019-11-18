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
        {auction}
        <tr>
            <td>{id}</td>
            <td>{number}</td>
            <td>{title}</td>
            <td>{section}</td>
            <td>{dateHolding}</td>
            <td class="cms_td_center">{actions}</td>
        </tr>
        {/auction}
    </tbody>
</table>

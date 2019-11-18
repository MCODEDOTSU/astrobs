<table cellpadding="0" cellspacing="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Наименование</th>
            <th>Предмет</th>
            <th>Дата начала работы</th>
            <th>Время старта и окончания</th>
            <th>Начальная цена</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {eauction}
        <tr>    
            <td>{number}</td>
            <td>{title}</td>
            <td>{subjectMunicipalContract}</td>
            <td>{dateStartWork}</td>
            <td>{timeStartOrEnd}</td>
            <td>{initialContractPrice}</td>
            <td class="cms_td_center">{actions}</td>
        </tr>
        {/eauction}
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Дата публикации</th>
            <th>Дата окончания приема</th>
            <th>Заказчик</th>
            <th>Номер</th>
            <th>Предмет закупки</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {quotations}
        <tr>    
            <td>{dateSubmissionQuotedApplications}</td>
            <td>{receptionDateClosed}</td>
            <td>{customer}</td>
            <td>{number}</td>
            <td>{nameCharacteristicsQuantityWorks}</td>
            <td class="cms_td_center">{actions}</td>
        </tr>
        {/quotations}
    </tbody>
</table>
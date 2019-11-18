<table cellspacing="0" cellpadding="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Клиент</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {orders}
            <tr>
                <td>{id}</td>
                <td>{client}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/orders}
    </tbody>
</table>

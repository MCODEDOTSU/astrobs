<table cellspacing="0" cellpadding="0" border="0" rel="datatables">

    <thead>
        <tr>
            <th>Номер</th>
            <th>Наименование</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {user_groups}
            <tr>
                <td>{id}</td>
                <td>{name}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/user_groups}
    </tbody>

</table>

<table cellpadding="0" cellspacing="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Название</th>
            <th>Родительский раздел</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        {sections}
            <tr>
                <td>{number}</td>
                <td>{title}</td>
                <td>{parent_section}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/sections}
    </tbody>
</table>
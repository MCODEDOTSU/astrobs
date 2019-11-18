<table cellspacing="0" cellpadding="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Ответ</th>
            <th>Количество голосов</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {answers}
            <tr>
                <td>{id}</td>
                <td>{text}</td>
                <td>{count}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/answers}
    </tbody>
</table>

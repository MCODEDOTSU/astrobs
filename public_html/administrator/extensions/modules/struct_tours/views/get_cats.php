<h1>{tname}</h1>
<table cellspacing="0" cellpadding="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Тур</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {tours}
            <tr>
                <td>{id}</td>
                <td>{title}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/tours}
    </tbody>
</table>

<div id="cms_bar"></div> 
     
<table cellpadding="0" cellspacing="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Название</th>
            <th>Тип</th>
            <th>Дата создания</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    
    <tbody>
        {file}
        <tr>
            <td>{title}</td>
            <td align="center">{type}</td>
            <td align="center">{created}</td>
            <td align="center">{actions}</td>
        </tr>
        {/file}
    </tbody>
</table>

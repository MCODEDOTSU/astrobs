<table border="0" cellpadding="0" cellspacing="0" rel="datatables">

    <thead>
        <tr>
            <th>Id</th>
            <th>Полное наименование</th>
            <th>Краткое наименование</th>
            <th>Организационно-правовая форма</th>
            <th>ИНН</th>
            <th>КПП</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {legalBody}
        <tr>
            <td>{id}</td>
            <td>{fullName}</td>
            <td>{shortName}</td>
            <td>{formEntity}</td>
            <td>{inn}</td>
            <td>{kpp}</td>
            <td>{actions}</td>
        </tr>
        {/legalBody}
    </tbody>

</table>
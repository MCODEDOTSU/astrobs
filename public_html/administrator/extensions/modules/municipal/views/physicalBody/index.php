<table border="0" cellpadding="0" cellspacing="0" rel="datatables">

    <thead>
        <tr>
            <th>Id</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>ИНН</th>
            <th>Дата рождения</th>
            <th>Действия</th>
        </tr>
    </thead>
    
    <tbody>
        {physicalBody}
        <tr>
            <td>{id}</td>
            <td>{surname}</td>
            <td>{name}</td>
            <td>{patronymic}</td>
            <td>{inn}</td>
            <td>{dateOfBirth}</td>
            <td>{actions}</td>
        </tr>
        {/physicalBody}
    </tbody>

</table>
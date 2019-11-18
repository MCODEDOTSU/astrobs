
<table cellspacing="0" cellpadding="0" border="0" rel="datatables">

    <thead>
        <tr>
            <th>Номер</th>
            <th>Имя пользователя</th>
            <th>Email</th>
            <th>Группа</th>
            <th>Роль</th>
            <th>Дата регистрации</th>
            <th>Последний визит</th>
            <th>Активный</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {users_entries}
            <tr>
                <td>{id}</td>        
                <td>{name}</td>        
                <td>{email}</td>        
                <td align="center">{group}</td>        
                <td align="center">{role}</td>        
                <td align="center">{created}</td>        
                <td align="center">{last_visit}</td>        
                <td align="center">{state}</td>        
                <td align="center">{actions}</td>        
            </tr>        
        {/users_entries}        
    </tbody>

</table>

<table cellspacing="0" cellpadding="0" border="0" rel="datatables">

    <thead>
        <tr>
            <th>Номер</th>
            <th>Название книги</th>
            <th>Автор</th>
            <th>Год</th>
            <th>Тематика</th>
            <th>Размер файла</th>
            <th>Создан</th>
            <th>Обновлен</th>
            <th>Действия</th>
        </tr>
    </thead>

    <tbody>
        {library}
            <tr>
                <td>{id}</td>
                <td>{name}</td>
                <td>{author}</td>
                <td>{year}</td>
                <td>{category}</td>
                <td>{size}kb</td>
                <td>{create}</td>
                <td>{update}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/library}
    </tbody>

</table>

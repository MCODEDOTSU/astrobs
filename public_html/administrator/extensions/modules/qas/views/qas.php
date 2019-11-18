<div id="cms_bar"></div>

<table cellspacing="0" cellpadding="0" border="0" rel="datatables">
    <thead>
        <tr>
            <th>Номер</th>
            <th>E-mail</th>
            <th>От кого</th>
            <th>Вопрос</th>
            <th>Ответ</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        {qas}
            <tr>
                <td>{id}</td>
                <td>{author_to}</td>
                <td>{author_from}</td>
                <td>{question}</td>
                <td>{answer}</td>
                <td>{created}</td>
                <td class="cms_td_center">{actions}</td>
            </tr>
        {/qas}
    </tbody>
</table>
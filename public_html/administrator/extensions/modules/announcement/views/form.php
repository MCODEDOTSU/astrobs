<h2><?=_module_icon('article')?>Создание / Редактирование объявления</h2>


{form_open}
{file}
<ul>
    <li>
        <label for="title">Заголовок</label>
        {title}
    </li>
    
    <li>
        {body}
    </li>
    
    <li class="cms_dialog_btn">
        <input type="submit" value="Сохранить">
    </li>
</ul>
{form_close}


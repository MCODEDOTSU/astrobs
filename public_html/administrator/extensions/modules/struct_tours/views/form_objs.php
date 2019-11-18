<h2>Создание / Редактирование категорий</h2>

{form_open}
{obj}
{cat}
    <ul class="form">
        <li>
            <label for="title">Название</label>
            {title}
        </li>
        
        <li>
            <label for="desc">Описание</label>
            {desc}
        </li>
		
		<li>
            <label for="seltour">Тур</label>
            {selcat}
        </li>
        
        <li>
            {submit}
        </li>
        
    </ul>
{form_close}
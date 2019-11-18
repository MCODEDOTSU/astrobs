<div class="cms_title"><img src="<?=base_url();?>cms_icons/user_add.png" align="absmiddle">Добавление / редактирование пользователя</div>

<div class="cms_content">
	{form_open}
		    <label for="name" style="width: 150px; display:block; float: left;">Имя пользователя</label>
		    {name}

			<br /><br />

		    <label for="email" style="width: 150px; display:block; float: left;">Email</label>
		    {email}

			<br /><br />

		    <label for="password" style="width: 150px; display:block; float: left;">Пароль</label>
		    {password}

			<br /><br />

		    <label for="confpwd" style="width: 150px; display:block; float: left;">Подтверждение пароля</label>
		    {confpwd}

			<br /><br />

		    <label for="group" style="width: 150px; display:block; float: left;">Группа</label>
		    {group}

			<br /><br />

		    <label for="role" style="width: 150px; display:block; float: left;">Роль</label>
		    {role}

			<br /><br />

		    <label for="state" style="width: 150px; display:block; float: left;">Активный ?</label>
		    {state}

			<br /><br />

		    {submit}
	{form_close} 
</div>

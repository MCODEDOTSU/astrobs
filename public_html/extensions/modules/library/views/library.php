<div id="library">

	<h1>Библиотека</h1>

	<table cellspacing="0" cellpadding="0" border="0" rel="datatables" width="100%" class="display">
		<thead>
		    <tr>
		        <th>Название книги</th>
		        <th>Автор</th>
		        <th>Год</th>
		        <th>Тематика</th>
		        <th>Размер файла</th>
		        <th>Скачать</th>
		    </tr>
		</thead>
		<tbody>
		    {library}
		        <tr>
		            <td>{name}</td>
		            <td>{author}</td>
		            <td>{year}</td>
		            <td>{category}</td>
		            <td>{size}kb</td>
		            <td>{download}</td>
		        </tr>
		    {/library}
		</tbody>
	</table>

</div>


